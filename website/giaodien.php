<?php

$option = isset($_GET['option']) ? $_GET['option'] : 'home';  
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechLaptop Store - Cửa hàng laptop hàng đầu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main2.css">
    <link rel="stylesheet" href="css/main3.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-custom-gradient">
        <div class="container-fluid">
            <a class="navbar-brand" href="?option=home">
                <img src="https://via.placeholder.com/30x30.png?text=TL" alt="" class="me-2"> TechLaptop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($option == 'home') ? 'active' : ''; ?>" href="?option=home">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($option == 'sanpham') ? 'active' : ''; ?>" href="?option=sanpham">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($option == 'lienhe') ? 'active' : ''; ?>" href="?option=lienhe">Liên hệ</a>
                    </li>
                   
                </ul>
                <form class="d-flex ms-auto" role="search">
                    <div class="search-container position-relative">
                    <div class="d-flex">
                        <input type="text" class="search-input" id="searchInput" placeholder="Tìm kiếm laptop (VD: Dell XPS, HP Gaming...)">
                        <button class="search-btn" onclick="performSearch()"><i class="fas fa-search"></i>Tìm kiếm</button>
                    </div>
                    <div class="autocomplete-suggestions" id="autocompleteSuggestions"></div>
                </div>
                </form>
                <ul class="navbar-nav ms-3">
                     <li class="nav-item">
                        <a class="nav-link <?php echo ($option == 'yeuthich') ? 'active' : ''; ?>" href="?option=yeuthich">
                            <i class="fas fa-heart" style="font-size: 1.5em;"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($option == 'giohang') ? 'active' : ''; ?>" href="?option=giohang">
                            <i class="fas fa-shopping-cart" style="font-size: 1.5em;"></i><span class="badge bg-danger" style="position: relative; bottom: 15px;">3</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($option == 'taikhoan') ? 'active' : ''; ?>" href="?option=taikhoan">Tài khoản</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../login.php">Đăng nhập</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
    <?php
          switch ($option) {
              case 'home':
                  include 'views/trangchu.php';
                  break;
              case 'sanpham':
                  include 'views/sanpham.php';
                  break;
                case 'lienhe':
                  include 'views/lienhe.php';
                  break;
                case 'yeuthich':
                  include 'views/yeuthich.php';
                  break;
                case 'chitietsanpham':
                  include 'views/chitietsanpham.php';
                  break;
                case 'giohang':
                  include 'views/giohang.php';
                  break;
                case 'thanhtoan':
                  include 'views/thanhtoan.php';
                  break;
                case 'taikhoan':
                  include 'views/taikhoan.php';
                  break;
              default:
                  include '404.php';
          }
          ?>
    </main>
  
    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4>TechLaptop</h4>
                    <p>Chuyên cung cấp laptop chất lượng cao từ các thương hiệu hàng đầu. Đảm bảo uy tín, giá cả cạnh tranh.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h4>Liên kết nhanh</h4>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Trang chủ</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Sản phẩm</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Chính sách bảo hành</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h4>Liên hệ</h4>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Đường Công Nghệ, TP.HCM</li>
                        <li><i class="fas fa-phone-alt me-2"></i> 0123 456 789</li>
                        <li><i class="fas fa-envelope me-2"></i> support@techlaptop.vn</li>
                        <li><i class="fas fa-clock me-2"></i> 8:00 - 22:00, Thứ 2 - Chủ Nhật</li>
                    </ul>
                </div>
            </div>
            <div class="text-center mt-4">
                <p class="mb-0">&copy; 2025 TechLaptop. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    <!-- Toast Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-check-circle text-success me-2"></i>
                <strong class="me-auto">Thành công</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Sản phẩm đã được thêm vào giỏ hàng!
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>