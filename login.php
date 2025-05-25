<?php
session_start();
require_once 'config/connect.php';

$error_message = ''; // Khởi tạo biến thông báo lỗi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $User = $_POST['tendangnhap'];
    $Pass = $_POST['matkhau'];

    // Kiểm tra đầu vào của người dùng
    if (empty($User) || empty($Pass)) {
        $error_message = "Tên đăng nhập và mật khẩu không được để trống!";
    } else {
        // Truy vấn cơ sở dữ liệu để lấy thông tin người dùng
        $sql = "SELECT * FROM nguoidung WHERE tendangnhap = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $User);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Kiểm tra mật khẩu
            if ($Pass == $user['matkhau']) {
                // Lưu thông tin vào session
                $_SESSION['id'] = $user['id'];
                $_SESSION['Chucvu'] = $user['Chucvu']; // Đảm bảo vai trò được lưu vào session
                if ($user['Chucvu'] == 'admin') {
                    header("Location: admin/admin.php");
                    exit();
                } else {
                    header("Location: website/giaodien.php");
                    exit();
                }
            } else {
                $error_message = "Tên đăng nhập hoặc mật khẩu không chính xác!";
            }
        } else {
            $error_message = "Tên đăng nhập hoặc mật khẩu không chính xác!";
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
    <title>Đăng Nhập</title>
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
        .login-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            max-width: 400px;
            width: 100%;
        }
        .login-container h2 {
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
        .btn-social {
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            color: #fff;
            margin: 0.5rem 0;
        }
        .btn-facebook {
            background-color: #3b5998;
        }
        .btn-google {
            background-color: #db4437;
        }
        .form-label {
            font-weight: 500;
            color: #555;
        }
        .forgot-password, .register-link {
            text-align: center;
            margin-top: 1rem;
        }
        .forgot-password a, .register-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-password a:hover, .register-link a:hover {
            text-decoration: underline;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: #777;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ddd;
            margin: 0 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng Nhập</h2>
        
        <!-- Thêm phần thông báo lỗi -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="tendangnhap" placeholder="Nhập tên đăng nhập" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="matkhau" placeholder="Nhập mật khẩu" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
        </form>
        <div class="forgot-password">
            <a href="forgot_password.php">Quên mật khẩu?</a>
        </div>
        <div class="register-link">
            <span>Bạn chưa có tài khoản? </span><a href="register.php">Đăng ký ngay</a>
        </div>
    </div>

    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>