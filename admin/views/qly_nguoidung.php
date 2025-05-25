<!-- qly_nguoidung.php -->
<?php
require_once '../config/connect.php';

// Lấy danh sách vai trò để lọc
$roles = ['admin', 'nhanvien', 'khachhang'];

// Xử lý tìm kiếm và lọc
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$role_filter = isset($_GET['role']) && in_array($_GET['role'], $roles) ? $_GET['role'] : '';

// Truy vấn danh sách người dùng
$query = "SELECT * FROM nguoidung WHERE 1=1";
if ($search) {
    $query .= " AND (tendangnhap LIKE '%$search%' OR email LIKE '%$search%')";
}
if ($role_filter) {
    $query .= " AND Chucvu = '$role_filter'";
}
$query .= " ORDER BY ngaytao DESC";
$result = mysqli_query($conn, $query);

// Xử lý thêm người dùng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $tendangnhap = mysqli_real_escape_string($conn, $_POST['tendangnhap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $matkhau = password_hash($_POST['matkhau'], PASSWORD_BCRYPT);
    $hoten = mysqli_real_escape_string($conn, $_POST['hoten']);
    $chucvu = in_array($_POST['chucvu'], $roles) ? $_POST['chucvu'] : 'khachhang';
    $sodienthoai = mysqli_real_escape_string($conn, $_POST['sodienthoai']);
    $diachi = mysqli_real_escape_string($conn, $_POST['diachi']);

    // Validate tên đăng nhập và email
    $check_query = "SELECT * FROM nguoidung WHERE tendangnhap = '$tendangnhap' OR email = '$email'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>Swal.fire('Lỗi', 'Tên đăng nhập hoặc email đã tồn tại!', 'error');</script>";
    } else {
        $insert_query = "INSERT INTO nguoidung (tendangnhap, matkhau, email, hoten, Chucvu, sodienthoai, diachi) 
                        VALUES ('$tendangnhap', '$matkhau', '$email', '$hoten', '$chucvu', '$sodienthoai', '$diachi')";
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>Swal.fire('Thành công', 'Thêm người dùng thành công!', 'success').then(() => { window.location.href = '?option=nguoidung'; });</script>";
        } else {
            echo "<script>Swal.fire('Lỗi', 'Không thể thêm người dùng!', 'error');</script>";
        }
    }
}

// Xử lý cập nhật người dùng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
    $id = (int)$_POST['id'];
    $tendangnhap = mysqli_real_escape_string($conn, $_POST['tendangnhap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $hoten = mysqli_real_escape_string($conn, $_POST['hoten']);
    $chucvu = in_array($_POST['chucvu'], $roles) ? $_POST['chucvu'] : 'khachhang';
    $sodienthoai = mysqli_real_escape_string($conn, $_POST['sodienthoai']);
    $diachi = mysqli_real_escape_string($conn, $_POST['diachi']);

    // Kiểm tra tên đăng nhập và email không trùng (trừ chính user đó)
    $check_query = "SELECT * FROM nguoidung WHERE (tendangnhap = '$tendangnhap' OR email = '$email') AND id != $id";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>Swal.fire('Lỗi', 'Tên đăng nhập hoặc email đã tồn tại!', 'error');</script>";
    } else {
        $update_query = "UPDATE nguoidung SET 
                        tendangnhap = '$tendangnhap', 
                        email = '$email', 
                        hoten = '$hoten', 
                        Chucvu = '$chucvu', 
                        sodienthoai = '$sodienthoai', 
                        diachi = '$diachi' 
                        WHERE id = $id";
        if (mysqli_query($conn, $update_query)) {
            echo "<script>Swal.fire('Thành công', 'Cập nhật người dùng thành công!', 'success').then(() => { window.location.href = '?option=nguoidung'; });</script>";
        } else {
            echo "<script>Swal.fire('Lỗi', 'Không thể cập nhật người dùng!', 'error');</script>";
        }
    }
}

// Xử lý reset mật khẩu qua AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $id = (int)$_POST['id'];
    $new_password = '123456'; // Mật khẩu mới không mã hóa
    $update_query = "UPDATE nguoidung SET matkhau = '$new_password' WHERE id = $id";
    if (mysqli_query($conn, $update_query)) {
        echo json_encode(['status' => 'success', 'message' => 'Reset mật khẩu thành công! Mật khẩu mới: 123456']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không thể reset mật khẩu!']);
    }
    exit;
}

// Xử lý xóa người dùng qua AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $id = (int)$_POST['id'];
    // Kiểm tra ràng buộc
    $check_orders = mysqli_query($conn, "SELECT * FROM donhang WHERE nguoidung_id = $id");
    $check_reviews = mysqli_query($conn, "SELECT * FROM danhgia WHERE nguoidung_id = $id");
    $check_cart = mysqli_query($conn, "SELECT * FROM giohang WHERE nguoidung_id = $id");
    if (mysqli_num_rows($check_orders) > 0 || mysqli_num_rows($check_reviews) > 0 || mysqli_num_rows($check_cart) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Không thể xóa vì người dùng có đơn hàng, đánh giá hoặc giỏ hàng liên kết!']);
    } else {
        $delete_query = "DELETE FROM nguoidung WHERE id = $id";
        if (mysqli_query($conn, $delete_query)) {
            echo json_encode(['status' => 'success', 'message' => 'Xóa người dùng thành công!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể xóa người dùng!']);
        }
    }
    exit;
}
?>

<!-- Giao diện chính -->
<div class="container-fluid py-4">
    <h1 class="mb-4">Quản Lý Người Dùng</h1>

    <!-- Form tìm kiếm và lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="option" value="nguoidung"> <!-- Giữ trạng thái option -->
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo tên đăng nhập hoặc email" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Tất cả vai trò</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role; ?>" <?php echo $role_filter == $role ? 'selected' : ''; ?>>
                                <?php echo ucfirst($role); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#addUserModal">Thêm người dùng</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bảng danh sách người dùng -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                            <th>Họ tên</th>
                            <th>Vai trò</th>
                            <th>Số điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['tendangnhap']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['hoten'] ?? 'N/A'); ?></td>
                                <td><?php echo ucfirst($row['Chucvu']); ?></td>
                                <td><?php echo htmlspecialchars($row['sodienthoai'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['diachi'] ?? 'N/A'); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['ngaytao'])); ?></td>
                                
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal" 
                                            onclick="fillEditForm(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['tendangnhap']); ?>', '<?php echo htmlspecialchars($row['email']); ?>', '<?php echo htmlspecialchars($row['hoten'] ?? ''); ?>', '<?php echo $row['Chucvu']; ?>', '<?php echo htmlspecialchars($row['sodienthoai'] ?? ''); ?>', '<?php echo htmlspecialchars($row['diachi'] ?? ''); ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-info" onclick="resetPassword(<?php echo $row['id']; ?>)">
                                        <i class="bi bi-key"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteUser(<?php echo $row['id']; ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm người dùng -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Thêm Người Dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="addUserForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" name="tendangnhap" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="matkhau" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Họ tên</label>
                            <input type="text" name="hoten" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vai trò</label>
                            <select name="chucvu" class="form-select" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?php echo $role; ?>"><?php echo ucfirst($role); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="sodienthoai" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="diachi" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="add_user" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa người dùng -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Chỉnh Sửa Người Dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="editUserForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" name="tendangnhap" id="edit_tendangnhap" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Họ tên</label>
                            <input type="text" name="hoten" id="edit_hoten" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vai trò</label>
                            <select name="chucvu" id="edit_chucvu" class="form-select" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?php echo $role; ?>"><?php echo ucfirst($role); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="sodienthoai" id="edit_sodienthoai" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="diachi" id="edit_diachi" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="edit_user" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- JavaScript xử lý AJAX -->
<script>
function fillEditForm(id, tendangnhap, email, hoten, chucvu, sodienthoai, diachi) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_tendangnhap').value = tendangnhap;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_hoten').value = hoten;
    document.getElementById('edit_chucvu').value = chucvu;
    document.getElementById('edit_sodienthoai').value = sodienthoai;
    document.getElementById('edit_diachi').value = diachi;
}

function resetPassword(id) {
    Swal.fire({
        title: 'Bạn có chắc?',
        text: 'Mật khẩu sẽ được reset thành 123456!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Reset',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `reset_password=true&id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: data.status === 'success' ? 'Thành công' : 'Lỗi',
                    text: data.message,
                    icon: data.status,
                    confirmButtonText: 'OK'
                }).then(() => {
                    if (data.status === 'success') {
                        window.location.href = '?option=nguoidung';
                    }
                });
            });
        }
    });
}

function deleteUser(id) {
    Swal.fire({
        title: 'Bạn có chắc?',
        text: 'Hành động này không thể hoàn tác!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `delete_user=true&id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: data.status === 'success' ? 'Thành công' : 'Lỗi',
                    text: data.message,
                    icon: data.status,
                    confirmButtonText: 'OK'
                }).then(() => {
                    if (data.status === 'success') {
                        window.location.href = '?option=nguoidung';
                    }
                });
            });
        }
    });
}
</script>