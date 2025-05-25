<?php
require_once '../config/connect.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$rating = isset($_GET['rating']) ? (int)$_GET['rating'] : 0;
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Xử lý xóa đánh giá
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM danhgia WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: 'Xóa đánh giá thành công!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = '?option=danhgia';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Xóa đánh giá thất bại!',
                showConfirmButton: true
            });
        </script>";
    }
    $stmt->close();
}

// Xây dựng truy vấn SQL
$sql = "SELECT d.id, d.sanpham_id, sp.ten AS sanpham_ten, d.nguoidung_id, nd.hoten AS nguoidung_ten, d.diemso, d.binhluan, d.ngaytao 
        FROM danhgia d 
        LEFT JOIN sanpham sp ON d.sanpham_id = sp.id 
        LEFT JOIN nguoidung nd ON d.nguoidung_id = nd.id 
        WHERE 1=1";

$params = [];
$types = "";

if ($search) {
    $sql .= " AND d.binhluan LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}
if ($product_id) {
    $sql .= " AND d.sanpham_id = ?";
    $params[] = $product_id;
    $types .= "i";
}
if ($user_id) {
    $sql .= " AND d.nguoidung_id = ?";
    $params[] = $user_id;
    $types .= "i";
}
if ($rating) {
    $sql .= " AND d.diemso = ?";
    $params[] = $rating;
    $types .= "i";
}
if ($date_from) {
    $sql .= " AND d.ngaytao >= ?";
    $params[] = $date_from . " 00:00:00";
    $types .= "s";
}
if ($date_to) {
    $sql .= " AND d.ngaytao <= ?";
    $params[] = $date_to . " 23:59:59";
    $types .= "s";
}

$sql .= " ORDER BY d.ngaytao DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Lấy danh sách sản phẩm và người dùng để lọc
$products = $conn->query("SELECT id, ten FROM sanpham ORDER BY ten");
$users = $conn->query("SELECT id, hoten FROM nguoidung WHERE hoten IS NOT NULL ORDER BY hoten");
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h2 class="fw-bold">Quản Lý Đánh Giá</h2>
</div>

<div class="table-container">
    <!-- Bộ lọc và tìm kiếm -->
    <form method="GET" class="mb-4">
        <input type="hidden" name="option" value="danhgia">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="product_id" class="form-label">Sản phẩm</label>
                <select name="product_id" id="product_id" class="form-select">
                    <option value="">Tất cả sản phẩm</option>
                    <?php while ($row = $products->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo $product_id == $row['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['ten']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="user_id" class="form-label">Người dùng</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">Tất cả người dùng</option>
                    <?php while ($row = $users->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo $user_id == $row['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['hoten']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="rating" class="form-label">Điểm số</label>
                <select name="rating" id="rating" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="1" <?php echo $rating == 1 ? 'selected' : ''; ?>>1 sao</option>
                    <option value="2" <?php echo $rating == 2 ? 'selected' : ''; ?>>2 sao</option>
                    <option value="3" <?php echo $rating == 3 ? 'selected' : ''; ?>>3 sao</option>
                    <option value="4" <?php echo $rating == 4 ? 'selected' : ''; ?>>4 sao</option>
                    <option value="5" <?php echo $rating == 5 ? 'selected' : ''; ?>>5 sao</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">Từ ngày</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo htmlspecialchars($date_from); ?>">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">Đến ngày</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="<?php echo htmlspecialchars($date_to); ?>">
            </div>
        </div>
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label for="search" class="form-label">Tìm kiếm bình luận</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Nhập nội dung bình luận..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-custom me-2">
                    <i class="bi bi-funnel"></i> Lọc
                </button>
                <a href="?option=danhgia" class="btn btn-secondary btn-custom">
                    <i class="bi bi-arrow-counterclockwise"></i> Xóa bộ lọc
                </a>
            </div>
        </div>
    </form>

    <!-- Bảng danh sách đánh giá -->
    <table class="table product-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Người dùng</th>
                <th>Điểm số</th>
                <th>Bình luận</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="product-row">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['sanpham_ten']); ?></td>
                        <td><?php echo htmlspecialchars($row['nguoidung_ten']); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star-fill text-warning <?php echo $i <= $row['diemso'] ? '' : 'text-muted'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($row['binhluan']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['ngaytao'])); ?></td>
                        <td>
                            <a href="#" class="btn btn-danger btn-action-custom delete-btn" 
                               data-id="<?php echo $row['id']; ?>" 
                               data-name="<?php echo htmlspecialchars($row['binhluan']); ?>">
                                <i class="bi bi-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Không có đánh giá nào phù hợp.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        Swal.fire({
            title: 'Xác nhận xóa',
            text: `Bạn có chắc muốn xóa đánh giá "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `?option=danhgia&action=delete&id=${id}`;
            }
        });
    });
});
</script>
