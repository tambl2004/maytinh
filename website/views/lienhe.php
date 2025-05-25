<?php
$isLoggedIn = isLoggedIn();
$userInfo = null;

if ($isLoggedIn) {
    $userId = $_SESSION['id'];
    $sql = "SELECT hoten, email, sodienthoai FROM nguoidung WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userInfo = $result->fetch_assoc();
    $stmt->close();
}
?>

<!-- Breadcrumb -->
<div class="container mt-4">
    <nav class="breadcrumb-custom">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?option=home" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active">Liên hệ</li>
        </ol>
    </nav>
</div>

<!-- Contact Section -->
<div class="container my-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="section-title display-4 text-center">Liên hệ với chúng tôi</h1>
    </div>

    <div class="row">
        <!-- Contact Information -->
        <div class="col-lg-4 mb-4">
            <div class="contact-info-card h-100">
                <div class="contact-header">
                    <h3 class="h4 mb-4">Thông tin liên hệ</h3>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-details">
                        <h5>Địa chỉ</h5>
                        <p>Xuân La, Tây Hồ, Hà Nội<br>Việt Nam</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="contact-details">
                        <h5>Điện thoại</h5>
                        <p><a href="tel:0969859400" class="contact-link">0969 859 400</a><br>
                        <a href="tel:0987654321" class="contact-link">0987 654 321</a></p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-details">
                        <h5>Email</h5>
                        <p><a href="mailto:vantamst97@gmail.com" class="contact-link">vantamst97@gmail.com</a><br>
                        <a href="mailto:sales@techlaptop.vn" class="contact-link">sales@techlaptop.vn</a></p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="contact-details">
                        <h5>Giờ làm việc</h5>
                        <p>Thứ 2 - Thứ 6: 8:00 - 18:00<br>
                        Thứ 7 - Chủ nhật: 9:00 - 17:00</p>
                    </div>
                </div>

                <div class="social-contact mt-4">
                    <h5 class="mb-3">Kết nối với chúng tôi</h5>
                    <div class="social-links-contact">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="contact-form-card">
                <div class="contact-form-header">
                    <h3 class="h4 mb-3">Gửi tin nhắn cho chúng tôi</h3>
                    <p class="text-muted mb-4">Điền thông tin vào form dưới đây, chúng tôi sẽ phản hồi trong vòng 24 giờ.</p>
                </div>

                <form id="contactForm" class="contact-form" action="process_contact.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fullName" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom" id="fullName" name="fullName" value="<?php echo $isLoggedIn && $userInfo['hoten'] ? htmlspecialchars($userInfo['hoten']) : ''; ?>" required <?php echo $isLoggedIn && $userInfo['hoten'] ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-custom" id="email" name="email" value="<?php echo $isLoggedIn && $userInfo['email'] ? htmlspecialchars($userInfo['email']) : ''; ?>" required <?php echo $isLoggedIn && $userInfo['email'] ? 'readonly' : ''; ?>>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control form-control-custom" id="phone" name="phone" value="<?php echo $isLoggedIn && $userInfo['sodienthoai'] ? htmlspecialchars($userInfo['sodienthoai']) : ''; ?>" <?php echo $isLoggedIn && $userInfo['sodienthoai'] ? 'readonly' : ''; ?>>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="subject" class="form-label">Chủ đề <span class="text-danger">*</span></label>
                            <select class="form-select form-control-custom" id="subject" name="subject" required>
                                <option value="">Chọn chủ đề</option>
                                <option value="product_inquiry">Tư vấn sản phẩm</option>
                                <option value="technical_support">Hỗ trợ kỹ thuật</option>
                                <option value="warranty">Bảo hành</option>
                                <option value="complaint">Khiếu nại</option>
                                <option value="cooperation">Hợp tác kinh doanh</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="message" class="form-label">Nội dung tin nhắn <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-custom" id="message" name="message" rows="6" placeholder="Nhập nội dung tin nhắn của bạn..." required></textarea>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="agreeTerms" name="agreeTerms" required>
                        <label class="form-check-label" for="agreeTerms">
                            Tôi đồng ý với <a href="#" class="text-decoration-none">điều khoản dịch vụ</a> và <a href="#" class="text-decoration-none">chính sách bảo mật</a> của TechLaptop
                        </label>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-submit-form">
                            <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                        </button>
                        <button type="reset" class="btn btn-reset-form">
                            <i class="fas fa-redo me-2"></i>Làm mới
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="map-container">
                <div class="map-header text-center mb-4">
                    <h3 class="section-title">Vị trí cửa hàng</h3>
                    <p class="text-muted">Hãy đến thăm showroom của chúng tôi để trải nghiệm trực tiếp các sản phẩm laptop tốt nhất</p>
                </div>
                
                <div class="map-wrapper">
                    <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d11820.600236008953!2d105.80784059237389!3d21.062443572034773!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1svi!2s!4v1748141279922!5m2!1svi!2s" 
                    class="map-iframe"
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Services Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="services-grid">
                <div class="text-center mb-4">
                    <h3 class="section-title">Dịch vụ hỗ trợ</h3>
                    <p class="text-muted">Chúng tôi cung cấp đầy đủ các dịch vụ hỗ trợ khách hàng</p>
                </div>
                
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="service-item">
                            <div class="service-icon">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <h5>Giao hàng nhanh</h5>
                            <p>Giao hàng miễn phí toàn quốc trong 24-48h</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="service-item">
                            <div class="service-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h5>Bảo trì sửa chữa</h5>
                            <p>Dịch vụ bảo trì và sửa chữa chuyên nghiệp</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="service-item">
                            <div class="service-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h5>Hỗ trợ 24/7</h5>
                            <p>Đội ngũ tư vấn sẵn sàng hỗ trợ mọi lúc</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="service-item">
                            <div class="service-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h5>Bảo hành chính hãng</h5>
                            <p>Bảo hành chính hãng từ 12-36 tháng</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['toast'])): ?>
        Swal.fire({
            icon: '<?php echo $_SESSION['toast']['type'] === 'success' ? 'success' : 'error'; ?>',
            title: '<?php echo $_SESSION['toast']['type'] === 'success' ? 'Thành công' : 'Lỗi'; ?>',
            html: '<?php echo $_SESSION['toast']['message']; ?>',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>
});
</script>