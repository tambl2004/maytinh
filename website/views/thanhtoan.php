<?php
// Giả lập dữ liệu giỏ hàng (trong thực tế sẽ lấy từ session hoặc database)
$cartItems = [
    [
        'id' => 1,
        'name' => 'Dell XPS 13 Plus',
        'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300',
        'price' => 45990000,
        'original_price' => 52990000,
        'quantity' => 1,
        'specs' => 'Intel Core i7-1360P, 16GB RAM, 512GB SSD'
    ],
    [
        'id' => 2,
        'name' => 'MacBook Pro 14 M3',
        'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300',
        'price' => 52990000,
        'original_price' => 0,
        'quantity' => 1,
        'specs' => 'Apple M3 Pro, 18GB RAM, 512GB SSD'
    ]
];

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping = 50000;
$discount = 1000000;
$total = $subtotal + $shipping - $discount;
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-custom">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?option=home">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="?option=giohang">Giỏ hàng</a></li>
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
                                <input type="text" class="form-control form-control-custom" name="fullname" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại *</label>
                                <input type="tel" class="form-control form-control-custom" name="phone" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control form-control-custom" name="email">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tỉnh/Thành phố *</label>
                                <select class="form-control form-control-custom" name="province" required>
                                    <option value="">Chọn tỉnh/thành</option>
                                    <option value="hcm">TP. Hồ Chí Minh</option>
                                    <option value="hn">Hà Nội</option>
                                    <option value="dn">Đà Nẵng</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quận/Huyện *</label>
                                <select class="form-control form-control-custom" name="district" required>
                                    <option value="">Chọn quận/huyện</option>
                                    <option value="q1">Quận 1</option>
                                    <option value="q3">Quận 3</option>
                                    <option value="q7">Quận 7</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Phường/Xã *</label>
                                <select class="form-control form-control-custom" name="ward" required>
                                    <option value="">Chọn phường/xã</option>
                                    <option value="p1">Phường 1</option>
                                    <option value="p2">Phường 2</option>
                                    <option value="p3">Phường 3</option>
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
                            <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                        </div>
                        <div class="checkout-item-details">
                            <div class="checkout-item-name"><?= $item['name'] ?></div>
                            <div class="checkout-item-specs"><?= $item['specs'] ?></div>
                            <div class="checkout-item-quantity">Số lượng: <?= $item['quantity'] ?></div>
                        </div>
                        <div class="checkout-item-price">
                            <div class="current-price"><?= number_format($item['price']) ?>₫</div>
                            <?php if ($item['original_price'] > 0): ?>
                                <div class="original-price"><?= number_format($item['original_price']) ?>₫</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Mã giảm giá -->
                <div class="coupon-checkout">
                    <div class="coupon-input-group">
                        <input type="text" class="coupon-input" placeholder="Nhập mã giảm giá">
                        <button class="btn-apply-coupon">Áp dụng</button>
                    </div>
                    <div class="coupon-tags mt-2">
                        <span class="coupon-tag">NEWCUSTOMER</span>
                        <span class="coupon-tag">SAVE100K</span>
                        <span class="coupon-tag">LAPTOP20</span>
                    </div>
                </div>
                
                <!-- Chi tiết giá -->
                <div class="price-breakdown">
                    <div class="price-item">
                        <span>Tạm tính:</span>
                        <span><?= number_format($subtotal) ?>₫</span>
                    </div>
                    <div class="price-item">
                        <span>Phí vận chuyển:</span>
                        <span id="shippingFee">50.000₫</span>
                    </div>
                    <div class="price-item discount-item">
                        <span>Giảm giá:</span>
                        <span>-<?= number_format($discount) ?>₫</span>
                    </div>
                    <div class="price-divider"></div>
                    <div class="price-total">
                        <span>Tổng cộng:</span>
                        <span id="totalAmount"><?= number_format($total) ?>₫</span>
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
// Update shipping fee based on selected method
document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const shippingFees = {
            'standard': 50000,
            'express': 100000,
            'same_day': 200000
        };
        
        const selectedFee = shippingFees[this.value];
        document.getElementById('shippingFee').textContent = new Intl.NumberFormat('vi-VN').format(selectedFee) + '₫';
        
        // Update total
        const subtotal = <?= $subtotal ?>;
        const discount = <?= $discount ?>;
        const newTotal = subtotal + selectedFee - discount;
        document.getElementById('totalAmount').textContent = new Intl.NumberFormat('vi-VN').format(newTotal) + '₫';
    });
});

// Place order function
function placeOrder() {
    const form = document.getElementById('checkoutForm');
    if (form.checkValidity()) {
        // Show success message
        const toastEl = document.getElementById('successToast');
        const toastMessage = document.getElementById('toastMessage');
        toastMessage.textContent = 'Đơn hàng đã được đặt thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.';
        
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
        
        // Simulate redirect after 3 seconds
        setTimeout(() => {
            window.location.href = '?option=home';
        }, 3000);
    } else {
        form.reportValidity();
    }
}

// Apply coupon
document.querySelector('.btn-apply-coupon').addEventListener('click', function() {
    const couponInput = document.querySelector('.coupon-input');
    const couponCode = couponInput.value.trim().toUpperCase();
    
    if (couponCode) {
        // Simulate coupon validation
        const validCoupons = {
            'NEWCUSTOMER': 500000,
            'SAVE100K': 100000,
            'LAPTOP20': 1000000
        };
        
        if (validCoupons[couponCode]) {
            const toastEl = document.getElementById('successToast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = `Mã giảm giá "${couponCode}" đã được áp dụng thành công!`;
            
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
            
            couponInput.value = '';
        } else {
            alert('Mã giảm giá không hợp lệ!');
        }
    }
});

// Quick apply coupon tags
document.querySelectorAll('.coupon-tag').forEach(tag => {
    tag.addEventListener('click', function() {
        document.querySelector('.coupon-input').value = this.textContent;
    });
});
</script>