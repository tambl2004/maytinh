<?php
// Hàm kiểm tra trạng thái đăng nhập
function isLoggedIn() {
    return isset($_SESSION['id']);
}       

// Hàm lấy vai trò của người dùng
function getUserRole() {
    return isset($_SESSION['Chucvu']) ? $_SESSION['Chucvu'] : null;
}

// Hàm đăng xuất
function logout() {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}

// Xử lý yêu cầu đăng xuất
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
}
?>