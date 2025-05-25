<?php
$userId = $_SESSION['id'];

// Lấy thông tin người dùng từ cơ sở dữ liệu
$sqlUser = "SELECT hoten, sodienthoai, email FROM nguoidung WHERE id = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$userInfo = $resultUser->fetch_assoc();
$stmtUser->close();

// Lấy giỏ hàng từ database
$sql = "SELECT g.sanpham_id, g.soluong, s.id, s.ten, s.gia, s.giacu, s.hinhanh, s.thongso
        FROM giohang g
        JOIN sanpham s ON g.sanpham_id = s.id
        WHERE g.nguoidung_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$subtotal = 0;
while ($row = $result->fetch_assoc()) {
    $row['quantity'] = $row['soluong'];
    $row['subtotal'] = $row['gia'] * $row['quantity'];
    $subtotal += $row['subtotal'];
    $cartItems[] = $row;
}
$stmt->close();

// Kiểm tra giỏ hàng rỗng
if (empty($cartItems)) {
    header('Location: ?option=giohang');
    exit;
}

// Lấy mã giảm giá từ session
$discount = isset($_SESSION['coupon']['discount']) ? $_SESSION['coupon']['discount'] : 0;
$shipping = 50000; // Phí vận chuyển mặc định
$total = $subtotal + $shipping - $discount;
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-custom">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?option=home" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="?option=giohang" class="text-decoration-none">Giỏ hàng</a></li>
            <li class="breadcrumb-item active">Thanh toán</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Form thanh toán -->
        <div class="col-lg-7">
            <div class="checkout-form-container">
                <!-- Thông tin giao hàng -->
                <div class="checkout-section">
                    <h4 class="section-title">
                        <i class="fas fa-shipping-fast me-2"></i>
                        Thông tin giao hàng
                    </h4>
                    
                    <form id="checkoutForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên *</label>
                                <input type="text" class="form-control form-control-custom" name="fullname" value="<?php echo htmlspecialchars($userInfo['hoten'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại *</label>
                                <input type="tel" class="form-control form-control-custom" name="phone" pattern="[0-9]{10,11}" value="<?php echo htmlspecialchars($userInfo['sodienthoai'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control form-control-custom" name="email" value="<?php echo htmlspecialchars($userInfo['email'] ?? ''); ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tỉnh/Thành phố *</label>
                                <select class="form-control form-control-custom" name="province" id="province" required>
                                    <option value="">Chọn tỉnh/thành</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quận/Huyện *</label>
                                <select class="form-control form-control-custom" name="district" id="district" required disabled>
                                    <option value="">Chọn quận/huyện</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Phường/Xã *</label>
                                <select class="form-control form-control-custom" name="ward" id="ward" required disabled>
                                    <option value="">Chọn phường/xã</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ cụ thể *</label>
                            <textarea class="form-control form-control-custom" name="address" rows="2" placeholder="Số nhà, tên đường..." required></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Ghi chú đơn hàng</label>
                            <textarea class="form-control form-control-custom" name="note" rows="3" placeholder="Ghi chú thêm về đơn hàng, thời gian giao hàng..."></textarea>
                        </div>
                    </form>
                </div>

                <!-- Phương thức giao hàng -->
                <div class="checkout-section">
                    <h4 class="section-title">
                        <i class="fas fa-truck me-2"></i>
                        Phương thức giao hàng
                    </h4>
                    
                    <div class="shipping-methods">
                        <div class="shipping-method">
                            <input type="radio" id="standard" name="shipping_method" value="standard" checked>
                            <label for="standard" class="shipping-label">
                                <div class="shipping-info">
                                    <div class="shipping-name">Giao hàng tiêu chuẩn</div>
                                    <div class="shipping-desc">2-3 ngày làm việc</div>
                                </div>
                                <div class="shipping-price">50.000₫</div>
                            </label>
                        </div>
                        <div class="shipping-method">
                            <input type="radio" id="express" name="shipping_method" value="express">
                            <label for="express" class="shipping-label">
                                <div class="shipping-info">
                                    <div class="shipping-name">Giao hàng nhanh</div>
                                    <div class="shipping-desc">1-2 ngày làm việc</div>
                                </div>
                                <div class="shipping-price">100.000₫</div>
                            </label>
                        </div>
                        <div class="shipping-method">
                            <input type="radio" id="same_day" name="shipping_method" value="same_day">
                            <label for="same_day" class="shipping-label">
                                <div class="shipping-info">
                                    <div class="shipping-name">Giao trong ngày</div>
                                    <div class="shipping-desc">Trong vòng 6 giờ (nội thành)</div>
                                </div>
                                <div class="shipping-price">200.000₫</div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="checkout-section">
                    <h4 class="section-title">
                        <i class="fas fa-credit-card me-2"></i>
                        Phương thức thanh toán
                    </h4>
                    
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" id="cod" name="payment_method" value="cod" checked>
                            <label for="cod" class="payment-label">
                                <i class="fas fa-money-bill-wave payment-icon"></i>
                                <div class="payment-info">
                                    <div class="payment-name">Thanh toán khi nhận hàng</div>
                                    <div class="payment-desc">Thanh toán bằng tiền mặt khi nhận hàng</div>
                                </div>
                            </label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer">
                            <label for="bank_transfer" class="payment-label">
                                <i class="fas fa-university payment-icon"></i>
                                <div class="payment-info">
                                    <div class="payment-name">Chuyển khoản ngân hàng</div>
                                    <div class="payment-desc">Chuyển khoản qua ATM, Internet Banking</div>
                                </div>
                            </label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" id="credit_card" name="payment_method" value="credit_card">
                            <label for="credit_card" class="payment-label">
                                <i class="fas fa-credit-card payment-icon"></i>
                                <div class="payment-info">
                                    <div class="payment-name">Thẻ tín dụng/Ghi nợ</div>
                                    <div class="payment-desc">Visa, Mastercard, JCB</div>
                                </div>
                            </label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" id="e_wallet" name="payment_method" value="e_wallet">
                            <label for="e_wallet" class="payment-label">
                                <i class="fas fa-mobile-alt payment-icon"></i>
                                <div class="payment-info">
                                    <div class="payment-name">Ví điện tử</div>
                                    <div class="payment-desc">MoMo, ZaloPay, ViettelPay</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tóm tắt đơn hàng -->
        <div class="col-lg-5">
            <div class="order-summary-checkout">
                <h4 class="section-title">
                    <i class="fas fa-receipt me-2"></i>
                    Tóm tắt đơn hàng
                </h4>
                
                <!-- Danh sách sản phẩm -->
                <div class="checkout-items">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="checkout-item">
                        <div class="checkout-item-image">
                            <img src="<?php echo htmlspecialchars($item['hinhanh']); ?>" alt="<?php echo htmlspecialchars($item['ten']); ?>">
                        </div>
                        <div class="checkout-item-details">
                            <div class="checkout-item-name"><?php echo htmlspecialchars($item['ten']); ?></div>
                            <div class="checkout-item-specs">
                                <?php
                                $thongso = json_decode($item['thongso'], true);
                                if (is_array($thongso)) {
                                    echo "CPU: " . htmlspecialchars($thongso['cpu'] ?? 'N/A') . ", RAM: " . htmlspecialchars($thongso['ram'] ?? 'N/A') . 
                                         ", Storage: " . htmlspecialchars($thongso['storage'] ?? 'N/A') . ", Screen: " . htmlspecialchars($thongso['screen'] ?? 'N/A');
                                } else {
                                    echo "Thông số: N/A";
                                }
                                ?>
                            </div>
                            <div class="checkout-item-quantity">Số lượng: <?php echo $item['quantity']; ?></div>
                        </div>
                        <div class="checkout-item-price">
                            <div class="current-price"><?php echo number_format($item['gia'], 0, ',', '.'); ?>₫</div>
                            <?php if ($item['giacu'] > 0): ?>
                                <div class="original-price"><?php echo number_format($item['giacu'], 0, ',', '.'); ?>₫</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Mã giảm giá -->
                <div class="coupon-checkout">
                    <div class="coupon-input-group">
                        <input type="text" class="coupon-input" placeholder="Nhập mã giảm giá" id="couponCode" value="<?php echo isset($_SESSION['coupon']['code']) ? htmlspecialchars($_SESSION['coupon']['code']) : ''; ?>">
                        <button class="btn-apply-coupon" onclick="applyCoupon()">Áp dụng</button>
                    </div>
                    <div class="coupon-tags mt-2">
                        <span class="coupon-tag" onclick="setCoupon('NEWCUSTOMER')">NEWCUSTOMER</span>
                        <span class="coupon-tag" onclick="setCoupon('SAVE100K')">SAVE100K</span>
                        <span class="coupon-tag" onclick="setCoupon('LAPTOP20')">LAPTOP20</span>
                    </div>
                </div>
                
                <!-- Chi tiết giá -->
                <div class="price-breakdown">
                    <div class="price-item">
                        <span>Tạm tính:</span>
                        <span id="subtotalAmount"><?php echo number_format($subtotal, 0, ',', '.'); ?>₫</span>
                    </div>
                    <div class="price-item">
                        <span>Phí vận chuyển:</span>
                        <span id="shippingFee"><?php echo number_format($shipping, 0, ',', '.'); ?>₫</span>
                    </div>
                    <div class="price-item discount-item">
                        <span>Giảm giá:</span>
                        <span id="discountAmount"><?php echo number_format($discount, 0, ',', '.'); ?>₫</span>
                    </div>
                    <div class="price-divider"></div>
                    <div class="price-total">
                        <span>Tổng cộng:</span>
                        <span id="totalAmount"><?php echo number_format($total, 0, ',', '.'); ?>₫</span>
                    </div>
                </div>
                
                <!-- Nút đặt hàng -->
                <button class="btn-place-order" onclick="placeOrder()">
                    <i class="fas fa-check-circle me-2"></i>
                    Đặt hàng
                </button>
                
                <!-- Chính sách bảo mật -->
                <div class="security-info">
                    <div class="security-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Thanh toán an toàn 100%</span>
                    </div>
                    <div class="security-item">
                        <i class="fas fa-lock"></i>
                        <span>Thông tin được mã hóa SSL</span>
                    </div>
                    <div class="security-item">
                        <i class="fas fa-undo"></i>
                        <span>Đổi trả trong 7 ngày</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Hàm định dạng số tiền
function numberFormat(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Hàm hiển thị thông báo SweetAlert2
function showToast(message, type = 'success') {
    Swal.fire({
        icon: type,
        title: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}

// Load provinces on page load
document.addEventListener('DOMContentLoaded', function() {
    fetch('https://provinces.open-api.vn/api/p/')
        .then(response => response.json())
        .then(data => {
            const provinceSelect = document.getElementById('province');
            data.forEach(province => {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
            provinceSelect.disabled = false;
        })
        .catch(error => {
            console.error('Lỗi khi tải danh sách tỉnh/thành phố:', error);
            showToast('Không thể tải danh sách tỉnh/thành phố', 'warning');
        });
});

// Load districts when province is selected
document.getElementById('province').addEventListener('change', function() {
    const provinceCode = this.value;
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
    districtSelect.disabled = true;
    wardSelect.disabled = true;

    if (provinceCode) {
        fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
            .then(response => response.json())
            .then(data => {
                data.districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.code;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
                districtSelect.disabled = false;
            })
            .catch(error => {
                console.error('Lỗi khi tải danh sách quận/huyện:', error);
                showToast('Không thể tải danh sách quận/huyện', 'warning');
            });
    }
});

// Load wards when district is selected
document.getElementById('district').addEventListener('change', function() {
    const districtCode = this.value;
    const wardSelect = document.getElementById('ward');
    
    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
    wardSelect.disabled = true;

    if (districtCode) {
        fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
            .then(response => response.json())
            .then(data => {
                data.wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.code;
                    option.textContent = ward.name;
                    wardSelect.appendChild(option);
                });
                wardSelect.disabled = false;
            })
            .catch(error => {
                console.error('Lỗi khi tải danh sách phường/xã:', error);
                showToast('Không thể tải danh sách phường/xã', 'warning');
            });
    }
});

// Cập nhật phí vận chuyển và tổng tiền khi thay đổi phương thức giao hàng
document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const shippingFees = {
            'standard': 50000,
            'express': 100000,
            'same_day': 200000
        };
        
        const selectedFee = shippingFees[this.value];
        document.getElementById('shippingFee').textContent = numberFormat(selectedFee) + '₫';
        
        const subtotal = <?php echo $subtotal; ?>;
        const discount = parseInt(document.getElementById('discountAmount').textContent.replace(/[^0-9]/g, '') || 0);
        const newTotal = subtotal + selectedFee - discount;
        document.getElementById('totalAmount').textContent = numberFormat(newTotal) + '₫';
    });
});

// Áp dụng mã giảm giá
function applyCoupon() {
    const couponCode = document.getElementById('couponCode').value.trim();
    if (couponCode) {
        fetch('controllers/coupon_controller.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=apply&code=${couponCode}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('discountAmount').textContent = numberFormat(data.discount) + '₫';
                const subtotal = <?php echo $subtotal; ?>;
                const shipping = document.querySelector('input[name="shipping_method"]:checked').value;
                const shippingFees = {
                    'standard': 50000,
                    'express': 100000,
                    'same_day': 200000
                };
                const newTotal = subtotal + shippingFees[shipping] - data.discount;
                document.getElementById('totalAmount').textContent = numberFormat(newTotal) + '₫';
                showToast(`Mã giảm giá "${couponCode}" đã được áp dụng thành công!`);
            } else {
                showToast(data.message || 'Mã giảm giá không hợp lệ!', 'warning');
            }
        })
        .catch(error => {
            showToast('Đã xảy ra lỗi hệ thống!', 'warning');
        });
    } else {
        showToast('Vui lòng nhập mã giảm giá', 'warning');
    }
}

function setCoupon(code) {
    document.getElementById('couponCode').value = code;
    applyCoupon();
}

// Đặt hàng
function placeOrder() {
    const form = document.getElementById('checkoutForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        showToast('Vui lòng điền đầy đủ thông tin bắt buộc', 'warning');
        return;
    }

    const formData = new FormData(form);
    const shippingMethod = document.querySelector('input[name="shipping_method"]:checked').value;
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

    // Lấy tên tỉnh, quận, phường từ các dropdown
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    if (!provinceSelect.value || !districtSelect.value || !wardSelect.value) {
        showToast('Vui lòng chọn đầy đủ tỉnh/thành, quận/huyện, phường/xã', 'warning');
        return;
    }

    const provinceName = provinceSelect.selectedOptions[0].text;
    const districtName = districtSelect.selectedOptions[0].text;
    const wardName = wardSelect.selectedOptions[0].text;

    const data = {
        action: 'place_order',
        fullname: formData.get('fullname'),
        phone: formData.get('phone'),
        email: formData.get('email'),
        province: provinceName,
        district: districtName,
        ward: wardName,
        address: formData.get('address'),
        note: formData.get('note'),
        shipping_method: shippingMethod,
        payment_method: paymentMethod
    };

    fetch('controllers/order_controller.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data).toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Đơn hàng đã được đặt thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
            setTimeout(() => {
                window.location.href = '?option=home';
            }, 3000);
        } else {
            showToast(data.message || 'Đặt hàng thất bại! Vui lòng thử lại.', 'warning');
        }
    })
    .catch(error => {
        showToast('Đã xảy ra lỗi hệ thống!', 'warning');
    });
}
</script>