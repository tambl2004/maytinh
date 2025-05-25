<?php
require_once '../config/connect.php';

// Hàm helper để chuyển đổi trạng thái
function getTrangThaiText($trangthai) {
    switch ($trangthai) {
        case 'moi':
            return 'Mới';
        case 'dangxuly':
            return 'Đang xử lý';
        case 'hoanthanh':
            return 'Hoàn thành';
        default:
            return ucfirst($trangthai);
    }
}

// Xử lý tìm kiếm và lọc
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, trim($_GET['status'])) : '';

// Xây dựng câu truy vấn
$sql = "SELECT * FROM lienhe WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (hoten LIKE '%$search%' OR email LIKE '%$search%' OR sodienthoai LIKE '%$search%' OR chude LIKE '%$search%')";
}

if ($status_filter) {
    $sql .= " AND trangthai = '$status_filter'";
}

$sql .= " ORDER BY ngaytao DESC";

// Phân trang
$items_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Đếm tổng số liên hệ
$count_sql = "SELECT COUNT(*) as total FROM lienhe WHERE 1=1";
if ($search) {
    $count_sql .= " AND (hoten LIKE '%$search%' OR email LIKE '%$search%' OR sodienthoai LIKE '%$search%' OR chude LIKE '%$search%')";
}
if ($status_filter) {
    $count_sql .= " AND trangthai = '$status_filter'";
}
$result_count = mysqli_query($conn, $count_sql);
$total_items = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_items / $items_per_page);

// Lấy danh sách liên hệ
$sql .= " LIMIT $items_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql);
$contacts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $contacts[] = $row;
}

// Xử lý xóa liên hệ
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $delete_sql = "DELETE FROM lienhe WHERE id = $id";
    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>Swal.fire('Thành công', 'Xóa liên hệ thành công', 'success').then(() => { window.location.href = '?option=lienhe'; });</script>";
    } else {
        echo "<script>Swal.fire('Lỗi', 'Xóa liên hệ thất bại', 'error');</script>";
    }
}

// Xử lý cập nhật trạng thái (trả lời)
if (isset($_POST['reply_submit'])) {
    $id = (int)$_POST['contact_id'];
    $trangthai = mysqli_real_escape_string($conn, $_POST['trangthai']);
    $update_sql = "UPDATE lienhe SET trangthai = '$trangthai' WHERE id = $id";
    if (mysqli_query($conn, $update_sql)) {
        // Gửi email phản hồi (giả lập, bạn có thể tích hợp thư viện gửi email như PHPMailer)
        echo "<script>Swal.fire('Thành công', 'Cập nhật trạng thái thành công', 'success').then(() => { window.location.href = '?option=lienhe'; });</script>";
    } else {
        echo "<script>Swal.fire('Lỗi', 'Cập nhật trạng thái thất bại', 'error');</script>";
    }
}
?>
    <!-- Page Header -->
    <div class="page-header">
        <h3 class="fw-bold">Quản Lý Liên Hệ</h3>
        <p class="text-muted">Xem, trả lời và quản lý các liên hệ từ khách hàng</p>
    </div>

    <!-- Tìm kiếm và lọc -->
    <div class="table-container mb-4">
        <form method="GET" class="row g-3">
            <input type="hidden" name="option" value="lienhe">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo tên, email, số điện thoại, chủ đề..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="moi" <?php echo $status_filter === 'moi' ? 'selected' : ''; ?>>Mới</option>
                    <option value="dangxuly" <?php echo $status_filter === 'dangxuly' ? 'selected' : ''; ?>>Đang xử lý</option>
                    <option value="hoanthanh" <?php echo $status_filter === 'hoanthanh' ? 'selected' : ''; ?>>Hoàn thành</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-custom w-100">Tìm kiếm</button>
            </div>
        </form>
    </div>

    <!-- Danh sách liên hệ -->
    <div class="table-container">
        <table class="table product-table contact-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Chủ đề</th>
                    <th>Trạng thái</th>
                    <th>Ngày gửi</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contacts)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Không có liên hệ nào</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contacts as $contact): ?>
                        <tr class="product-row">
                            <td><?php echo htmlspecialchars($contact['id']); ?></td>
                            <td><?php echo htmlspecialchars($contact['hoten']); ?></td>
                            <td><?php echo htmlspecialchars($contact['email']); ?></td>
                            <td><?php echo htmlspecialchars($contact['sodienthoai']); ?></td>
                            <td><?php echo htmlspecialchars($contact['chude']); ?></td>
                            <td>
                                <span class="status-badge <?php
                                    echo $contact['trangthai'] === 'moi' ? 'bg-danger-gradient' :
                                        ($contact['trangthai'] === 'dangxuly' ? 'bg-warning-gradient' : 'bg-success-gradient');
                                ?>">
                                    <?php echo getTrangThaiText($contact['trangthai']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($contact['ngaytao'])); ?></td>
                            <td>
                                <button class="btn btn-primary btn-action-custom" data-bs-toggle="modal" data-bs-target="#contactModal<?php echo $contact['id']; ?>">
                                    <i class="bi bi-eye"></i> Xem
                                </button>
                                <a href="?option=lienhe&action=delete&id=<?php echo $contact['id']; ?>" class="btn btn-danger btn-action-custom" onclick="return confirmDelete();">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?option=lienhe&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&page=<?php echo $page - 1; ?>">Trước</a>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?option=lienhe&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?option=lienhe&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&page=<?php echo $page + 1; ?>">Sau</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Modal xem chi tiết và trả lời -->
    <?php foreach ($contacts as $contact): ?>
        <div class="modal fade" id="contactModal<?php echo $contact['id']; ?>" tabindex="-1" aria-labelledby="contactModalLabel<?php echo $contact['id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contactModalLabel<?php echo $contact['id']; ?>">Chi tiết liên hệ #<?php echo $contact['id']; ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($contact['hoten']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($contact['email']); ?></p>
                                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($contact['sodienthoai']); ?></p>
                                <p><strong>Chủ đề:</strong> <?php echo htmlspecialchars($contact['chude']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Trạng thái:</strong> <?php echo getTrangThaiText($contact['trangthai']); ?></p>
                                <p><strong>Ngày gửi:</strong> <?php echo date('d/m/Y H:i', strtotime($contact['ngaytao'])); ?></p>
                            </div>
                        </div>
                        <hr>
                        <p><strong>Nội dung:</strong></p>
                        <p><?php echo nl2br(htmlspecialchars($contact['noidung'])); ?></p>
                        <hr>
                        <form method="POST">
                            <input type="hidden" name="contact_id" value="<?php echo $contact['id']; ?>">
                            <div class="mb-3">
                                <label for="trangthai<?php echo $contact['id']; ?>" class="form-label">Cập nhật trạng thái</label>
                                <select name="trangthai" id="trangthai<?php echo $contact['id']; ?>" class="form-select">
                                    <option value="moi" <?php echo $contact['trangthai'] === 'moi' ? 'selected' : ''; ?>>Mới</option>
                                    <option value="dangxuly" <?php echo $contact['trangthai'] === 'dangxuly' ? 'selected' : ''; ?>>Đang xử lý</option>
                                    <option value="hoanthanh" <?php echo $contact['trangthai'] === 'hoanthanh' ? 'selected' : ''; ?>>Hoàn thành</option>
                                </select>
                            </div>
                            <button type="submit" name="reply_submit" class="btn btn-primary btn-custom">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>


<script>
function confirmDelete() {
    return Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc muốn xóa liên hệ này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        return result.isConfirmed;
    });
}
</script>

