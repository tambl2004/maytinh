<?php
session_start();
include '../inc/auth.php';
include '../config/connect.php';
$option = isset($_GET['option']) ? $_GET['option'] : 'home';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quản Lý Cửa Hàng Laptop</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar px-0" id="sidebar">
                <div class="position-sticky pt-0">
                    <!-- Admin Profile Section -->
                    <div class="admin-profile">
                        <div class="admin-avatar">
                            <!-- Replace with actual user image -->
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face" alt="Admin">
                        </div>
                        <div class="admin-name">Nguyễn Văn Admin</div>
                        <div class="admin-role">Quản trị viên</div>
                        <div class="admin-actions">
                            <a href="?option=profile" class="admin-action-btn">
                                <i class="bi bi-person"></i> Hồ sơ
                            </a>
                            <a href="?action=logout" class="admin-action-btn">
                                <i class="bi bi-box-arrow-right"></i> Thoát
                            </a>
                        </div>
                    </div>
                    
                    <!-- Dashboard Section -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($option == 'home') ? 'active' : ''; ?> d-flex align-items-center justify-content-between" href="?option=home">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-house-door"></i>
                                    <span>Dashboard</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Product Management -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($option == 'sanpham') ? 'active' : ''; ?> d-flex align-items-center justify-content-between" href="?option=sanpham">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-laptop"></i>
                                    <span>Sản phẩm </span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($option == 'danhmuc') ? 'active' : ''; ?> d-flex align-items-center justify-content-between" href="?option=danhmuc">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-grid-3x3-gap"></i>
                                    <span>Danh mục</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($option == 'thuonghieu') ? 'active' : ''; ?> d-flex align-items-center justify-content-between" href="?option=thuonghieu">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-award"></i>
                                    <span>Thương hiệu</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Order Management -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($option == 'donhang') ? 'active' : ''; ?> d-flex align-items-center justify-content-between" href="?option=donhang">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-bag-check"></i>
                                    <span>Đơn hàng</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($option == 'magiamgia') ? 'active' : ''; ?> d-flex align-items-center justify-content-between" href="?option=magiamgia">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-percent"></i>
                                    <span>Mã giảm giá</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($option == 'danhgia') ? 'active' : ''; ?> d-flex align-items-center justify-content-between" href="?option=danhgia">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-star"></i>
                                    <span>Đánh giá</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    
                    <!-- System Management -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($option == 'baocao') ? 'active' : ''; ?> d-flex align-items-center justify-content-between" href="?option=baocao">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-bar-graph"></i>
                                    <span>Báo cáo</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($option == 'lienhe') ? 'active' : ''; ?> d-flex align-items-center justify-content-between" href="?option=lienhe">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope"></i>
                                    <span>Liên hệ</span>
                                </div>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($option == 'nguoidung') ? 'active' : ''; ?> d-flex align-items-center justify-content-between" href="?option=nguoidung">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-gear"></i>
                                    <span>Người dùng</span>
                                </div>
                            </a>
                        </li>
                    </ul>

                </div>  
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <?php
                require_once '../config/connect.php'; 
               
                switch ($option) {
                    case 'home':
                        include 'views/tongquan.php'; 
                        break;

                    case 'sanpham':
                        include 'views/qly_sanpham.php';
                        break;
                    case 'danhmuc':
                        include 'views/qly_danhmuc.php';
                        break;
                    case 'thuonghieu':
                        include 'views/qly_thuonghieu.php';
                        break;
                    case 'donhang':
                        include 'views/qly_donhang.php';
                        break;
                    case 'magiamgia':
                        include 'views/qly_magiamgia.php';
                        break;
                    case 'danhgia':
                        include 'views/qly_danhgia.php';
                        break;
                    case 'lienhe':
                        include 'views/qly_lienhe.php';
                        break;
                    case 'profile':
                        include 'views/taikhoancuatoi.php';
                        break;
                    case 'baocao':
                        include 'views/baocaothongke.php';
                        break;
                    case 'nguoidung':
                        include 'views/qly_nguoidung.php';
                        break;
                    default:
                        include 'views/404.php';
                }
            ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = event.target.closest('button');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggleButton) {
                sidebar.classList.remove('show');
            }
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['19/05', '20/05', '21/05', '22/05', '23/05', '24/05', '25/05'],
                datasets: [{
                    label: 'Doanh thu (triệu VNĐ)',
                    data: [35, 42, 38, 50, 48, 55, 45],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#007bff',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                elements: {
                    point: {
                        hoverRadius: 8
                    }
                }
            }
        });

        // Product Chart
        const productCtx = document.getElementById('productChart').getContext('2d');
        const productChart = new Chart(productCtx, {
            type: 'doughnut',
            data: {
                labels: ['MacBook', 'Dell', 'HP', 'ASUS', 'Lenovo'],
                datasets: [{
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: [
                        '#007bff',
                        '#28a745',
                        '#ffc107',
                        '#dc3545',
                        '#6f42c1'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // Navigation active state
       

        // Auto-refresh stats (simulation)
        setInterval(() => {
            // Simulate real-time updates
            const revenueElement = document.querySelector('.stats-card .h4');
            if (revenueElement) {
                const currentValue = parseInt(revenueElement.textContent.replace(/[₫,]/g, ''));
                const newValue = currentValue + Math.floor(Math.random() * 1000000 - 500000);
                revenueElement.textContent = '₫' + newValue.toLocaleString('vi-VN');
            }
        }, 30000); // Update every 30 seconds
    </script>
</body>
</html>