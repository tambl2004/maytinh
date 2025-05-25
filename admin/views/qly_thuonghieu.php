<?php
require_once '../config/connect.php';

// Xử lý thêm thương hiệu
if (isset($_POST['add_brand'])) {
    $ten = trim($_POST['ten']);
    $duongdan = trim($_POST['duongdan']);
    $mota = trim($_POST['mota']);
    $logo = '';

    // Validate
    $errors = [];
    if (empty($ten)) $errors[] = 'Tên thương hiệu không được để trống';
    if (empty($duongdan)) $errors[] = 'Đường dẫn không được để trống';
    
    // Kiểm tra đường dẫn duy nhất
    $sql = "SELECT COUNT(*) FROM thuonghieu WHERE duongdan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $duongdan);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if ($count > 0) $errors[] = 'Đường dẫn đã tồn tại';

    // Xử lý upload logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Logo chỉ hỗ trợ định dạng JPG, JPEG, PNG, GIF';
        } else {
            $logo = 'uploads/brands/' . time() . '_' . $_FILES['logo']['name'];
            if (!is_dir('../uploads/brands')) {
                mkdir('../uploads/brands', 0777, true);
            }
            move_uploaded_file($_FILES['logo']['tmp_name'], '../' . $logo);
        }
    }

    if (empty($errors)) {
        $sql = "INSERT INTO thuonghieu (ten, duongdan, logo, mota) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $ten, $duongdan, $logo, $mota);
        if ($stmt->execute()) {
            echo "<script>Swal.fire('Thành công', 'Thêm thương hiệu thành công', 'success').then(() => window.location.href='?option=thuonghieu');</script>";
        } else {
            echo "<script>Swal.fire('Lỗi', 'Thêm thương hiệu thất bại', 'error');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>Swal.fire('Lỗi', '" . implode('<br>', $errors) . "', 'error');</script>";
    }
}

// Xử lý cập nhật thương hiệu
if (isset($_POST['update_brand'])) {
    $id = $_POST['id'];
    $ten = trim($_POST['ten']);
    $duongdan = trim($_POST['duongdan']);
    $mota = trim($_POST['mota']);
    $current_logo = $_POST['current_logo'];

    // Validate
    $errors = [];
    if (empty($ten)) $errors[] = 'Tên thương hiệu không được để trống';
    if (empty($duongdan)) $errors[] = 'Đường dẫn không được để trống';

    // Kiểm tra đường dẫn duy nhất
    $sql = "SELECT COUNT(*) FROM thuonghieu WHERE duongdan = ? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $duongdan, $id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if ($count > 0) $errors[] = 'Đường dẫn đã tồn tại';

    // Xử lý upload logo mới
    $logo = $current_logo;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed)) {
            $errors[] = 'Logo chỉ hỗ trợ định dạng JPG, JPEG, PNG, GIF';
        } else {
            $logo = 'uploads/brands/' . time() . '_' . $_FILES['logo']['name'];
            if (!is_dir('../uploads/brands')) {
                mkdir('../uploads/brands', 0777, true);
            }
            move_uploaded_file($_FILES['logo']['tmp_name'], '../' . $logo);
            // Xóa logo cũ nếu có
            if ($current_logo && file_exists('../' . $current_logo)) {
                unlink('../' . $current_logo);
            }
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE thuonghieu SET ten = ?, duongdan = ?, logo = ?, mota = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $ten, $duongdan, $logo, $mota, $id);
        if ($stmt->execute()) {
            echo "<script>Swal.fire('Thành công', 'Cập nhật thương hiệu thành công', 'success').then(() => window.location.href='?option=thuonghieu');</script>";
        } else {
            echo "<script>Swal.fire('Lỗi', 'Cập nhật thương hiệu thất bại', 'error');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>Swal.fire('Lỗi', '" . implode('<br>', $errors) . "', 'error');</script>";
    }
}

// Xử lý xóa thương hiệu
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Kiểm tra xem thương hiệu có sản phẩm liên kết không
    $sql = "SELECT COUNT(*) FROM sanpham WHERE thuonghieu_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<script>Swal.fire('Lỗi', 'Không thể xóa thương hiệu vì có sản phẩm liên kết', 'error');</script>";
    } else {
        // Lấy thông tin logo để xóa file
        $sql = "SELECT logo FROM thuonghieu WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $logo = $row['logo'];
        $stmt->close();

        $sql = "DELETE FROM thuonghieu WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Xóa file logo nếu tồn tại
            if ($logo && file_exists('../' . $logo)) {
                unlink('../' . $logo);
            }
            echo "<script>Swal.fire('Thành công', 'Xóa thương hiệu thành công', 'success').then(() => window.location.href='?option=thuonghieu');</script>";
        } else {
            echo "<script>Swal.fire('Lỗi', 'Xóa thương hiệu thất bại', 'error');</script>";
        }
        $stmt->close();
    }
}

// Tìm kiếm thương hiệu
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = $search ? "WHERE ten LIKE ?" : "";
$sql = "SELECT * FROM thuonghieu $where ORDER BY ngaytao DESC";
$stmt = $conn->prepare($sql);
if ($search) {
    $search_param = "%$search%";
    $stmt->bind_param("s", $search_param);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h2 class="fw-bold">Quản lý thương hiệu</h2>
    <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#addBrandModal">
        <i class="bi bi-plus-circle"></i> Thêm thương hiệu
    </button>
</div>

<!-- Form tìm kiếm -->
<div class="table-container mb-4">
    <form method="GET" class="mb-3">
        <input type="hidden" name="option" value="thuonghieu">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm thương hiệu..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary btn-custom"><i class="bi bi-search"></i> Tìm</button>
        </div>
    </form>
</div>

<!-- Danh sách thương hiệu -->
<div class="table-container">
    <table class="product-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Logo</th>
                <th>Tên</th>
                <th>Đường dẫn</th>
                <th>Mô tả</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="product-row">
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <?php if ($row['logo']): ?>
                            <img src="../<?php echo htmlspecialchars($row['logo']); ?>" class="product-image" alt="Logo">
                        <?php else: ?>
                            <span class="text-muted">Chưa có logo</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['ten']); ?></td>
                    <td><?php echo htmlspecialchars($row['duongdan']); ?></td>
                    <td><?php echo htmlspecialchars($row['mota'] ?: 'Không có mô tả'); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['ngaytao'])); ?></td>
                    <td>
                        <button class="btn btn-primary btn-action btn-action-custom" data-bs-toggle="modal" data-bs-target="#editBrandModal" 
                                onclick="fillEditForm(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['ten'])); ?>', '<?php echo htmlspecialchars(addslashes($row['duongdan'])); ?>', '<?php echo htmlspecialchars(addslashes($row['mota'])); ?>', '<?php echo htmlspecialchars($row['logo']); ?>')">
                            <i class="bi bi-pencil"></i> Sửa
                        </button>
                        <button class="btn btn-danger btn-action btn-action-custom" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Không tìm thấy thương hiệu nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal thêm thương hiệu -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary-gradient">
                <h5 class="modal-title text-white" id="addBrandModalLabel">Thêm thương hiệu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                        <input type="text" name="ten" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Đường dẫn <span class="text-danger">*</span></label>
                        <input type="text" name="duongdan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="mota" class="form-control" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-custom" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="add_brand" class="btn btn-primary btn-custom">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa thương hiệu -->
<div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary-gradient">
                <h5 class="modal-title text-white" id="editBrandModalLabel">Chỉnh sửa thương hiệu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                        <input type="text" name="ten" id="edit_ten" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Đường dẫn <span class="text-danger">*</span></label>
                        <input type="text" name="duongdan" id="edit_duongdan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo hiện tại</label>
                        <input type="hidden" name="current_logo" id="edit_current_logo">
                        <div id="current_logo_preview" class="img-preview"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logo mới</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="mota" id="edit_mota" class="form-control" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-custom" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="update_brand" class="btn btn-primary btn-custom">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function fillEditForm(id, ten, duongdan, mota, logo) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_ten').value = ten;
    document.getElementById('edit_duongdan').value = duongdan;
    document.getElementById('edit_mota').value = mota;
    document.getElementById('edit_current_logo').value = logo;
    document.getElementById('current_logo_preview').innerHTML = logo ? `<img src="../${logo}" class="img-preview" alt="Logo hiện tại">` : 'Chưa có logo';
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc muốn xóa thương hiệu này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?option=thuonghieu&delete=' + id;
        }
    });
}
</script>