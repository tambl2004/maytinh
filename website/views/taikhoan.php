<?php

$userId = $_SESSION['id'];

// Lấy thông tin người dùng từ CSDL
$sql = "SELECT * FROM nguoidung WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$error_message = '';
$success_message = '';

// Xử lý cập nhật thông tin cá nhân
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $hoten = trim($_POST['hoten']);
    $email = trim($_POST['email']);
    $sodienthoai = trim($_POST['sodienthoai']);
    $diachi = trim($_POST['diachi']);

    // Validate đầu vào
    if (empty($hoten) || empty($email) || empty($sodienthoai) || empty($diachi)) {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Email không hợp lệ!";
    } elseif (!preg_match('/^[0-9]{10,11}$/', $sodienthoai)) {
        $error_message = "Số điện thoại phải từ 10-11 chữ số!";
    } else {
        // Cập nhật thông tin vào CSDL
        $sql = "UPDATE nguoidung SET hoten = ?, email = ?, sodienthoai = ?, diachi = ?, ngaycapnhat = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $hoten, $email, $sodienthoai, $diachi, $userId);
        if ($stmt->execute()) {
            $success_message = "Cập nhật thông tin thành công!";
            $user = array_merge($user, ['hoten' => $hoten, 'email' => $email, 'sodienthoai' => $sodienthoai, 'diachi' => $diachi]); // Cập nhật lại thông tin
        } else {
            $error_message = "Có lỗi xảy ra khi cập nhật thông tin!";
        }
        $stmt->close();
    }
}

// Xử lý đổi mật khẩu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $currentPassword = trim($_POST['currentPassword']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Validate đầu vào
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error_message = "Vui lòng điền đầy đủ thông tin!";
    } elseif ($newPassword !== $confirmPassword) {
        $error_message = "Mật khẩu mới và xác nhận mật khẩu không khớp!";
    } elseif (strlen($newPassword) < 6) {
        $error_message = "Mật khẩu mới phải có ít nhất 6 ký tự!";
    } else {
        // Kiểm tra mật khẩu hiện tại
        $sql = "SELECT matkhau FROM nguoidung WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $storedPassword = $result->fetch_assoc()['matkhau'];
        $stmt->close();

        // So sánh mật khẩu hiện tại (giả sử dùng plaintext, bạn nên mã hóa nếu cần)
        if ($currentPassword !== $storedPassword) {
            $error_message = "Mật khẩu hiện tại không đúng!";
        } else {
            // Mã hóa mật khẩu mới (sử dụng password_hash nếu cần)
            $hashedPassword = $newPassword; // Thay bằng password_hash($newPassword, PASSWORD_DEFAULT) nếu dùng mã hóa
            $sql = "UPDATE nguoidung SET matkhau = ?, ngaycapnhat = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $hashedPassword, $userId);
            if ($stmt->execute()) {
                $success_message = "Đổi mật khẩu thành công!";
            } else {
                $error_message = "Có lỗi xảy ra khi đổi mật khẩu!";
            }
            $stmt->close();
        }
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <!-- Sidebar điều hướng -->
        <div class="col-lg-3 mb-4">
            <div class="account-sidebar filter-container">
                <h3 class="section-title">Tài khoản của bạn</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#personal-info" data-bs-toggle="tab">Thông tin cá nhân</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#change-password" data-bs-toggle="tab">Đổi mật khẩu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#order-history" data-bs-toggle="tab">Lịch sử đơn hàng</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Nội dung chính -->
        <div class="col-lg-9">
            <div class="tab-content tab-content-custom">
                <!-- Thông tin cá nhân -->
                <div class="tab-pane fade show active" id="personal-info">
                    <div class="contact-form-card">
                        <h3 class="section-title">Thông tin cá nhân</h3>
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <input type="hidden" name="update_profile">
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control form-control-custom" id="fullName" name="hoten" value="<?php echo htmlspecialchars($user['hoten'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control form-control-custom" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control form-control-custom" id="phone" name="sodienthoai" value="<?php echo htmlspecialchars($user['sodienthoai'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <textarea class="form-control form-control-custom" id="address" name="diachi" rows="3" required><?php echo htmlspecialchars($user['diachi'] ?? ''); ?></textarea>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-submit-form">Lưu thay đổi</button>
                                <button type="reset" class="btn btn-reset-form">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Đổi mật khẩu -->
                <div class="tab-pane fade" id="change-password">
                    <div class="contact-form-card">
                        <h3 class="section-title">Đổi mật khẩu</h3>
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <input type="hidden" name="change_password">
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" class="form-control form-control-custom" id="currentPassword" name="currentPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control form-control-custom" id="newPassword" name="newPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control form-control-custom" id="confirmPassword" name="confirmPassword" required>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-submit-form">Lưu thay đổi</button>
                                <button type="reset" class="btn btn-reset-form">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lịch sử đơn hàng -->
                <div class="tab-pane fade" id="order-history">
                    <div class="cart-container">
                        <h3 class="section-title">Lịch sử đơn hàng</h3>
                        <div class="table-responsive">
                            <table class="table specs-table">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Trạng thái</th>
                                        <th>Tổng tiền</th>
                                        <th>Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM donhang WHERE nguoidung_id = ? ORDER BY ngaytao DESC";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param('i', $userId);
                                    $stmt->execute();
                                    $orders = $stmt->get_result();

                                    if ($orders->num_rows > 0) {
                                        while ($order = $orders->fetch_assoc()) {
                                            $statusBadge = '';
                                            switch ($order['trangthai']) {
                                                case 'choxuly':
                                                    $statusBadge = '<span class="badge bg-secondary">Chờ xử lý</span>';
                                                    break;
                                                case 'dangxuly':
                                                    $statusBadge = '<span class="badge bg-warning">Đang xử lý</span>';
                                                    break;
                                                case 'dagiao':
                                                    $statusBadge = '<span class="badge bg-info">Đã giao</span>';
                                                    break;
                                                case 'hoanthanh':
                                                    $statusBadge = '<span class="badge bg-success">Hoàn thành</span>';
                                                    break;
                                                case 'dahuy':
                                                    $statusBadge = '<span class="badge bg-danger">Đã hủy</span>';
                                                    break;
                                            }
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars('#DH' . str_pad($order['id'], 5, '0', STR_PAD_LEFT)) . '</td>';
                                            echo '<td>' . date('d/m/Y', strtotime($order['ngaytao'])) . '</td>';
                                            echo '<td>' . $statusBadge . '</td>';
                                            echo '<td>' . number_format($order['tienthucte'], 0, ',', '.') . ' VNĐ</td>';
                                            echo '<td><button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#orderDetailModal_' . $order['id'] . '">Xem chi tiết</button></td>';
                                            echo '</tr>';

                                            // Modal chi tiết đơn hàng
                                            echo '<div class="modal fade" id="orderDetailModal_' . $order['id'] . '" tabindex="-1" aria-labelledby="orderDetailModalLabel_' . $order['id'] . '" aria-hidden="true">';
                                            echo '<div class="modal-dialog modal-lg">';
                                            echo '<div class="modal-content">';
                                            echo '<div class="modal-header">';
                                            echo '<h5 class="modal-title" id="orderDetailModalLabel_' . $order['id'] . '">Chi tiết đơn hàng #' . htmlspecialchars('DH' . str_pad($order['id'], 5, '0', STR_PAD_LEFT)) . '</h5>';
                                            echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                                            echo '</div>';
                                            echo '<div class="modal-body">';
                                            echo '<div class="cart-container">';
                                            echo '<h6 class="mb-3">Thông tin đơn hàng</h6>';
                                            echo '<p><strong>Ngày đặt:</strong> ' . date('d/m/Y', strtotime($order['ngaytao'])) . '</p>';
                                            echo '<p><strong>Trạng thái:</strong> ' . $statusBadge . '</p>';
                                            echo '<p><strong>Tổng tiền:</strong> ' . number_format($order['tienthucte'], 0, ',', '.') . ' VNĐ</p>';
                                            echo '<hr>';
                                            echo '<h6>Sản phẩm</h6>';

                                            // Lấy chi tiết đơn hàng
                                            $orderId = $order['id'];
                                            $sqlDetail = "SELECT ctdh.*, sp.ten FROM chitietdonhang ctdh JOIN sanpham sp ON ctdh.sanpham_id = sp.id WHERE ctdh.donhang_id = ?";
                                            $stmtDetail = $conn->prepare($sqlDetail);
                                            $stmtDetail->bind_param('i', $orderId);
                                            $stmtDetail->execute();
                                            $details = $stmtDetail->get_result();

                                            while ($detail = $details->fetch_assoc()) {
                                                echo '<div class="cart-item">';
                                                echo '<div class="row align-items-center">';
                                                echo '<div class="col-md-2">';
                                                echo '<div class="cart-item-image">';
                                                echo '<img src="https://via.placeholder.com/120" alt="' . htmlspecialchars($detail['ten']) . '">';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '<div class="col-md-4">';
                                                echo '<h6 class="cart-item-title">' . htmlspecialchars($detail['ten']) . '</h6>';
                                                echo '<p class="cart-item-specs">Số lượng: ' . $detail['soluong'] . '</p>';
                                                echo '</div>';
                                                echo '<div class="col-md-3 text-end">';
                                                echo '<p class="current-price-cart">' . number_format($detail['gia'], 0, ',', '.') . ' VNĐ</p>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                            $stmtDetail->close();

                                            echo '</div>';
                                            echo '</div>';
                                            echo '<div class="modal-footer">';
                                            echo '<button type="button" class="btn btn-custom" data-bs-dismiss="modal">Đóng</button>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="5" class="text-center">Chưa có đơn hàng nào.</td></tr>';
                                    }
                                    $stmt->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <style>
        .form-control-custom { border-radius: 8px; padding: 0.75rem; }
        .btn-submit-form { background-color: #007bff; color: #fff; border: none; border-radius: 8px; padding: 0.75rem 1.5rem; }
        .btn-submit-form:hover { background-color: #0056b3; }
        .btn-reset-form { background-color: #6c757d; color: #fff; border: none; border-radius: 8px; padding: 0.75rem 1.5rem; }
        .btn-reset-form:hover { background-color: #5a6268; }
        .btn-custom { background-color: #007bff; color: #fff; border-radius: 8px; padding: 0.5rem 1rem; }
        .btn-custom:hover { background-color: #0056b3; }
        .contact-form-card { background: #fff; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 2rem; }
        .section-title { font-weight: 700; color: #333; margin-bottom: 1.5rem; }
        .alert { margin-bottom: 1rem; border-radius: 8px; }
    </style>