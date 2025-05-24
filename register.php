<?php
require_once 'config/connect.php';
session_start();

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Email = $_POST['email'];
    $User = $_POST['tendangnhap'];
    $Pass = $_POST['matkhau'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra đầu vào của người dùng
    if (empty($Email) || empty($User) || empty($Pass) || empty($confirm_password)) {
        $error_message = "Tất cả các trường đều bắt buộc!";
    } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Địa chỉ email không đúng định dạng!";
    } elseif (strlen($Pass) < 6) {
        $error_message = "Mật khẩu phải có ít nhất 6 ký tự!";
    } elseif ($Pass !== $confirm_password) {
        $error_message = "Mật khẩu xác nhận không khớp!";
    } else {
        // Kiểm tra xem tên đăng nhập hoặc email đã tồn tại chưa
        $sql = "SELECT * FROM nguoidung WHERE tendangnhap = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $User, $Email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Tên đăng nhập hoặc email đã tồn tại!";
        } else {
            // Thêm người dùng mới vào cơ sở dữ liệu
            $sql = "INSERT INTO nguoidung (tendangnhap, matkhau, email, Chucvu) VALUES (?, ?, ?, 'khachhang')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $User, $Pass, $Email);
            if ($stmt->execute()) {
                $success_message = "Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.";
            } else {
                $error_message = "Có lỗi xảy ra. Vui lòng thử lại.";
            }
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            max-width: 400px;
            width: 100%;
        }
        .register-container h2 {
            font-weight: 700;
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-label {
            font-weight: 500;
            color: #555;
        }
        .login-link {
            text-align: center;
            margin-top: 1rem;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Đăng Ký</h2>
        
        <!-- Thêm phần thông báo -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php elseif (!empty($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="tendangnhap" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="tendangnhap" name="tendangnhap" placeholder="Nhập tên đăng nhập" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" required>
            </div>
            <div class="mb-3">
                <label for="matkhau" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="matkhau" name="matkhau" placeholder="Nhập mật khẩu" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Đăng Ký</button>
        </form>
        <div class="login-link">
            <span>Bạn đã có tài khoản? </span><a href="login.php">Đăng nhập ngay</a>
        </div>
    </div>

    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Client-side validation -->
    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const matkhau = document.getElementById('matkhau').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            if (matkhau !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu và xác nhận mật khẩu không khớp!');
            }
        });
    </script>
</body>
</html>