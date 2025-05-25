<?php
include '../config/connect.php';

// Xử lý thêm danh mục
if (isset($_POST['add_category'])) {
    $ten = trim($_POST['ten']);
    $duongdan = trim($_POST['duongdan']);
    $mota = trim($_POST['mota']);
    
    // Validate
    $errors = [];
    if (empty($ten)) {
        $errors[] = "Tên danh mục không được để trống.";
    }
    if (empty($duongdan)) {
        $errors[] = "Đường dẫn không được để trống.";
    } else {
        // Kiểm tra đường dẫn trùng lặp
        $sql_check = "SELECT * FROM danhmuc WHERE duongdan = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "s", $duongdan);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        if (mysqli_num_rows($result_check) > 0) {
            $errors[] = "Đường dẫn đã tồn tại.";
        }
    }
    
    if (empty($errors)) {
        $sql = "INSERT INTO danhmuc (ten, duongdan, mota) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $ten, $duongdan, $mota);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>Swal.fire('Thành công', 'Thêm danh mục thành công!', 'success');</script>";
        } else {
            echo "<script>Swal.fire('Lỗi', 'Không thể thêm danh mục.', 'error');</script>";
        }
    } else {
        $error_message = implode("<br>", $errors);
        echo "<script>Swal.fire('Lỗi', '$error_message', 'error');</script>";
    }
}

// Xử lý chỉnh sửa danh mục
if (isset($_POST['edit_category'])) {
    $id = $_POST['id'];
    $ten = trim($_POST['ten']);
    $duongdan = trim($_POST['duongdan']);
    $mota = trim($_POST['mota']);
    
    // Validate
    $errors = [];
    if (empty($ten)) {
        $errors[] = "Tên danh mục không được để trống.";
    }
    if (empty($duongdan)) {
        $errors[] = "Đường dẫn không được để trống.";
    } else {
        // Kiểm tra đường dẫn trùng lặp
        $sql_check = "SELECT * FROM danhmuc WHERE duongdan = ? AND id != ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "si", $duongdan, $id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        if (mysqli_num_rows($result_check) > 0) {
            $errors[] = "Đường dẫn đã tồn tại.";
        }
    }
    
    if (empty($errors)) {
        $sql = "UPDATE danhmuc SET ten = ?, duongdan = ?, mota = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $ten, $duongdan, $mota, $id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>Swal.fire('Thành công', 'Cập nhật danh mục thành công!', 'success');</script>";
        } else {
            echo "<script>Swal.fire('Lỗi', 'Không thể cập nhật danh mục.', 'error');</script>";
        }
    } else {
        $error_message = implode("<br>", $errors);
        echo "<script>Swal.fire('Lỗi', '$error_message', 'error');</script>";
    }
}

// Xử lý xóa danh mục
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    
    // Kiểm tra xem danh mục có sản phẩm liên kết không
    $sql_check = "SELECT * FROM sanpham WHERE danhmuc_id = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $id);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>Swal.fire('Lỗi', 'Danh mục này đang có sản phẩm liên kết. Không thể xóa.', 'error');</script>";
    } else {
        $sql = "DELETE FROM danhmuc WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>Swal.fire('Thành công', 'Xóa danh mục thành công!', 'success');</script>";
        } else {
            echo "<script>Swal.fire('Lỗi', 'Không thể xóa danh mục.', 'error');</script>";
        }
    }
}

// Tìm kiếm và lọc danh mục
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';

$sql = "SELECT * FROM danhmuc WHERE 1=1";
$params = [];
$types = '';

if (!empty($search)) {
    $sql .= " AND (ten LIKE ? OR duongdan LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

if (!empty($date_filter)) {
    $sql .= " AND DATE(ngaytao) = ?";
    $params[] = $date_filter;
    $types .= "s";
}

$sql .= " ORDER BY ngaytao DESC";
$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$categories = mysqli_stmt_get_result($stmt);
?>
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h2">Quản lý danh mục</h1>
            <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="bi bi-plus-circle"></i> Thêm danh mục</button>
        </div>
    </div>

    <!-- Form tìm kiếm và lọc -->
    <div class="table-container mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="option" value="danhmuc">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên hoặc đường dẫn" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-4">
                    <input type="date" name="date_filter" class="form-control" value="<?php echo htmlspecialchars($date_filter); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-custom w-100"><i class="bi bi-search"></i> Tìm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách danh mục -->
    <div class="table-container">
        <div class="card-header">
            Danh sách danh mục
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Đường dẫn</th>
                            <th>Mô tả</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($categories)) { ?>
                            <tr class="product-row">
                                <td class="text-center align-middle"><?php echo $row['id']; ?></td>
                                <td class="text-center align-middle"><?php echo htmlspecialchars($row['ten']); ?></td>
                                <td class="text-center align-middle"><?php echo htmlspecialchars($row['duongdan']); ?></td>
                                <td class="text-center align-middle"><?php echo htmlspecialchars($row['mota'] ?? ''); ?></td>
                                <td class="text-center align-middle"><?php echo date('d/m/Y H:i', strtotime($row['ngaytao'])); ?></td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-primary btn-action-custom edit-category" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-id="<?php echo $row['id']; ?>" data-ten="<?php echo htmlspecialchars($row['ten']); ?>" data-duongdan="<?php echo htmlspecialchars($row['duongdan']); ?>" data-mota="<?php echo htmlspecialchars($row['mota'] ?? ''); ?>"><i class="bi bi-pencil"></i> Sửa</button>
                                    <a href="?option=danhmuc&delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-action-custom delete-category"><i class="bi bi-trash"></i> Xóa</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal thêm danh mục -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Thêm danh mục mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="ten" class="form-label">Tên danh mục</label>
                            <input type="text" class="form-control" id="ten" name="ten" required>
                        </div>
                        <div class="mb-3">
                            <label for="duongdan" class="form-label">Đường dẫn</label>
                            <input type="text" class="form-control" id="duongdan" name="duongdan" required>
                        </div>
                        <div class="mb-3">
                            <label for="mota" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="mota" name="mota" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-custom" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" name="add_category" class="btn btn-primary btn-custom">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa danh mục -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Chỉnh sửa danh mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_ten" class="form-label">Tên danh mục</label>
                            <input type="text" class="form-control" id="edit_ten" name="ten" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_duongdan" class="form-label">Đường dẫn</label>
                            <input type="text" class="form-control" id="edit_duongdan" name="duongdan" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_mota" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="edit_mota" name="mota" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-custom" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" name="edit_category" class="btn btn-primary btn-custom">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



<script>
// Điền dữ liệu vào modal chỉnh sửa
document.querySelectorAll('.edit-category').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('edit_id').value = this.dataset.id;
        document.getElementById('edit_ten').value = this.dataset.ten;
        document.getElementById('edit_duongdan').value = this.dataset.duongdan;
        document.getElementById('edit_mota').value = this.dataset.mota || '';
    });
});

// Xác nhận trước khi xóa
document.querySelectorAll('.delete-category').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.href;
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa danh mục này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});
</script>