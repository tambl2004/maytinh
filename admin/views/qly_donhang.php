<?php
// Kết nối database
require_once '../config/connect.php';

// Thêm vào đầu file qly_donhang.php
$trangThaiLabels = [
    'choxuly' => 'Chờ Xử Lý',
    'dangxuly' => 'Đang Xử Lý',
    'dagiao' => 'Đã Giao',
    'hoanthanh' => 'Hoàn Thành',
    'dahuy' => 'Đã Hủy'
];

$trangThaiThanhToanLabels = [
    'choxuly' => 'Chờ Xử Lý',
    'dathanhtoan' => 'Đã Thanh Toán',
    'thatbai' => 'Thất Bại'
];

// Xử lý tìm kiếm và lọc
$search = isset($_GET['search']) ? $_GET['search'] : '';
$trangthai = isset($_GET['trangthai']) ? $_GET['trangthai'] : '';
$phuongthucthanhtoan = isset($_GET['phuongthucthanhtoan']) ? $_GET['phuongthucthanhtoan'] : '';
$trangthaithanhtoan = isset($_GET['trangthaithanhtoan']) ? $_GET['trangthaithanhtoan'] : '';

$sql = "SELECT * FROM donhang WHERE 1=1";
$params = [];
if ($search) {
    $sql .= " AND (id LIKE ? OR hoten LIKE ? OR email LIKE ?)";
    $params = ["%$search%", "%$search%", "%$search%"];
}
if ($trangthai) {
    $sql .= " AND trangthai = ?";
    $params[] = $trangthai;
}
if ($phuongthucthanhtoan) {
    $sql .= " AND phuongthucthanhtoan = ?";
    $params[] = $phuongthucthanhtoan;
}
if ($trangthaithanhtoan) {
    $sql .= " AND trangthaithanhtoan = ?";
    $params[] = $trangthaithanhtoan;
}
$sql .= " ORDER BY ngaytao DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$orders = $stmt->get_result();

// Xử lý cập nhật trạng thái
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $trangthai = $_POST['trangthai'];
    $trangthaithanhtoan = $_POST['trangthaithanhtoan'];
    $ghichu = $_POST['ghichu'];

    $update_sql = "UPDATE donhang SET trangthai = ?, trangthaithanhtoan = ?, ghichu = ?, ngaycapnhat = NOW() WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('sssi', $trangthai, $trangthaithanhtoan, $ghichu, $order_id);
    if ($update_stmt->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Cập nhật thành công!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = '?option=donhang';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Không thể cập nhật trạng thái đơn hàng.',
                showConfirmButton: true
            });
        </script>";
    }
}
?>

<div class="page-header">
    <h3 class="mb-0">Quản Lý Đơn Hàng</h3>
</div>

<!-- Bộ lọc và tìm kiếm -->
<div class="table-container mb-4">
    <form method="GET" class="row g-3">
        <input type="hidden" name="option" value="donhang">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Tìm theo mã, tên, email..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="col-md-2">
            <select name="trangthai" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="choxuly" <?php echo $trangthai == 'choxuly' ? 'selected' : ''; ?>>Chờ xử lý</option>
                <option value="dangxuly" <?php echo $trangthai == 'dangxuly' ? 'selected' : ''; ?>>Đang xử lý</option>
                <option value="dagiao" <?php echo $trangthai == 'dagiao' ? 'selected' : ''; ?>>Đã giao</option>
                <option value="hoanthanh" <?php echo $trangthai == 'hoanthanh' ? 'selected' : ''; ?>>Hoàn thành</option>
                <option value="dahuy" <?php echo $trangthai == 'dahuy' ? 'selected' : ''; ?>>Đã hủy</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="phuongthucthanhtoan" class="form-select">
                <option value="">Tất cả phương thức</option>
                <option value="cod" <?php echo $phuongthucthanhtoan == 'cod' ? 'selected' : ''; ?>>COD</option>
                <option value="bank_transfer" <?php echo $phuongthucthanhtoan == 'bank_transfer' ? 'selected' : ''; ?>>Chuyển khoản</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="trangthaithanhtoan" class="form-select">
                <option value="">Tất cả trạng thái thanh toán</option>
                <option value="choxuly" <?php echo $trangthaithanhtoan == 'choxuly' ? 'selected' : ''; ?>>Chờ xử lý</option>
                <option value="dathanhtoan" <?php echo $trangthaithanhtoan == 'dathanhtoan' ? 'selected' : ''; ?>>Đã thanh toán</option>
                <option value="thatbai" <?php echo $trangthaithanhtoan == 'thatbai' ? 'selected' : ''; ?>>Thất bại</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-custom w-100">Lọc</button>
        </div>
    </form>
</div>

<!-- Bảng danh sách đơn hàng -->
<div class="table-container">
    <table class="table product-table">
        <thead>
            <tr>
                <th>Mã ĐH</th>
                <th>Khách hàng</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Trạng thái</th>
                <th>Thanh toán</th>
                <th>Ghi chú</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $orders->fetch_assoc()): ?>
                <tr class="product-row">
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['hoten']); ?></td>
                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                    <td><?php echo htmlspecialchars($order['sodienthoai']); ?></td>
                    <td>
                        <span class="status-badge <?php echo $order['trangthai'] == 'hoanthanh' ? 'bg-success-gradient' : ($order['trangthai'] == 'dahuy' ? 'bg-danger-gradient' : 'bg-warning-gradient'); ?>">
                            <?php echo $trangThaiLabels[$order['trangthai']]; ?>
                        </span>
                    </td>
                    <td>
                        <span class="status-badge <?php echo $order['trangthaithanhtoan'] == 'dathanhtoan' ? 'bg-success-gradient' : ($order['trangthaithanhtoan'] == 'thatbai' ? 'bg-danger-gradient' : 'bg-warning-gradient'); ?>">
                            <?php echo $trangThaiThanhToanLabels[$order['trangthaithanhtoan']]; ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($order['ghichu'] ?? ''); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['ngaytao'])); ?></td>
                    <td>
                        <button class="btn btn-primary btn-action-custom btn-action" data-bs-toggle="modal" data-bs-target="#viewOrderModal<?php echo $order['id']; ?>">
                            <i class="bi bi-eye"></i> Xem
                        </button>
                        <?php if ($order['trangthai'] != 'hoanthanh'): ?>
                        <button class="btn btn-primary btn-action-custom btn-action" data-bs-toggle="modal" data-bs-target="#editOrderModal<?php echo $order['id']; ?>">
                            <i class="bi bi-pencil"></i> Sửa
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php 
// Reset con trỏ kết quả để có thể dùng lại
$orders->data_seek(0);
// Hiển thị các modal
while ($order = $orders->fetch_assoc()): ?>
    <!-- Modal xem chi tiết đơn hàng -->
    <div class="modal fade" id="viewOrderModal<?php echo $order['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Chi tiết đơn hàng #<?php echo $order['id']; ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Thông tin khách hàng</h6>
                                </div>
                                <div class="card-body">
                                    <p><i class="bi bi-person-fill me-2"></i><strong>Họ tên:</strong> <?php echo htmlspecialchars($order['hoten']); ?></p>
                                    <p><i class="bi bi-envelope-fill me-2"></i><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                                    <p><i class="bi bi-telephone-fill me-2"></i><strong>SĐT:</strong> <?php echo htmlspecialchars($order['sodienthoai']); ?></p>
                                    <p><i class="bi bi-geo-alt-fill me-2"></i><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['diachi']); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Thông tin đơn hàng</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Trạng thái:</strong> 
                                        <span class="badge <?php echo $order['trangthai'] == 'hoanthanh' ? 'bg-success' : ($order['trangthai'] == 'dahuy' ? 'bg-danger' : 'bg-warning'); ?>">
                                            <?php echo $trangThaiLabels[$order['trangthai']]; ?>
                                        </span>
                                    </p>
                                    <p><strong>Thanh toán:</strong> 
                                        <span class="badge <?php echo $order['trangthaithanhtoan'] == 'dathanhtoan' ? 'bg-success' : ($order['trangthaithanhtoan'] == 'thatbai' ? 'bg-danger' : 'bg-warning'); ?>">
                                            <?php echo $trangThaiThanhToanLabels[$order['trangthaithanhtoan']]; ?>
                                        </span>
                                    </p>
                                    <p><strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($order['ngaytao'])); ?></p>
                                    <p><strong>Ghi chú:</strong> <?php echo htmlspecialchars($order['ghichu'] ?? 'Không có'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6>Chi tiết đơn hàng</h6>
                    <table class="table product-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare("SELECT c.*, s.ten FROM chitietdonhang c JOIN sanpham s ON c.sanpham_id = s.id WHERE c.donhang_id = ?");
                            $stmt->bind_param('i', $order['id']);
                            $stmt->execute();
                            $details = $stmt->get_result();
                            while ($detail = $details->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($detail['ten']); ?></td>
                                    <td><?php echo $detail['soluong']; ?></td>
                                    <td><?php echo number_format($detail['gia'], 0, ',', '.') . ' ₫'; ?></td>
                                    <td><?php echo number_format($detail['tong'], 0, ',', '.') . ' ₫'; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <h6>Thông tin thanh toán</h6>
                    <p><strong>Tổng tiền sản phẩm:</strong> <?php echo number_format($order['tongtien'], 0, ',', '.') . ' ₫'; ?></p>
                    <p><strong>Phí vận chuyển:</strong> <?php echo number_format($order['phivanchuyen'], 0, ',', '.') . ' ₫'; ?></p>
                    <p><strong>Giảm giá:</strong> <?php echo number_format($order['tiengiamgia'], 0, ',', '.') . ' ₫'; ?></p>
                    <p><strong>Tổng thực tế:</strong> <?php echo number_format($order['tienthucte'], 0, ',', '.') . ' ₫'; ?></p>
                    <?php if ($order['magiamgia_id']): ?>
                        <?php
                        $stmt = $conn->prepare("SELECT code FROM magiamgia WHERE id = ?");
                        $stmt->bind_param('i', $order['magiamgia_id']);
                        $stmt->execute();
                        $magiamgia = $stmt->get_result()->fetch_assoc();
                        ?>
                        <p><strong>Mã giảm giá:</strong> <?php echo htmlspecialchars($magiamgia['code']); ?></p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <?php if ($order['trangthai'] == 'hoanthanh'): ?>
                    <button type="button" class="btn btn-primary" onclick="window.open('views/inhoadon.php?id=<?php echo $order['id']; ?>', '_blank', 'width=800,height=600');">
                        <i class="bi bi-printer"></i> In hóa đơn
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal sửa đơn hàng -->
    <div class="modal fade" id="editOrderModal<?php echo $order['id']; ?>" tabindex="-1" aria-labelledby="editOrderModalLabel<?php echo $order['id']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderModalLabel<?php echo $order['id']; ?>">Cập nhật đơn hàng #<?php echo $order['id']; ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Trạng thái đơn hàng</label>
                            <select name="trangthai" class="form-select" required>
                                <option value="choxuly" <?php echo $order['trangthai'] == 'choxuly' ? 'selected' : ''; ?>>Chờ xử lý</option>
                                <option value="dangxuly" <?php echo $order['trangthai'] == 'dangxuly' ? 'selected' : ''; ?>>Đang xử lý</option>
                                <option value="dagiao" <?php echo $order['trangthai'] == 'dagiao' ? 'selected' : ''; ?>>Đã giao</option>
                                <option value="hoanthanh" <?php echo $order['trangthai'] == 'hoanthanh' ? 'selected' : ''; ?>>Hoàn thành</option>
                                <option value="dahuy" <?php echo $order['trangthai'] == 'dahuy' ? 'selected' : ''; ?>>Đã hủy</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trạng thái thanh toán</label>
                            <select name="trangthaithanhtoan" class="form-select" required>
                                <option value="choxuly" <?php echo $order['trangthaithanhtoan'] == 'choxuly' ? 'selected' : ''; ?>>Chờ xử lý</option>
                                <option value="dathanhtoan" <?php echo $order['trangthaithanhtoan'] == 'dathanhtoan' ? 'selected' : ''; ?>>Đã thanh toán</option>
                                <option value="thatbai" <?php echo $order['trangthaithanhtoan'] == 'thatbai' ? 'selected' : ''; ?>>Thất bại</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú (Lý do hủy nếu có)</label>
                            <textarea name="ghichu" class="form-control" rows="4"><?php echo htmlspecialchars($order['ghichu'] ?? ''); ?></textarea>
                        </div>
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <input type="hidden" name="update_status" value="1">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary btn-custom">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endwhile; ?>