<?php
$userId = $_SESSION['id'];

// Lấy giỏ hàng từ database
$sql = "SELECT g.sanpham_id, g.soluong, s.id, s.ten, s.gia, s.giacu, s.hinhanh, s.thongso, s.soluongton,
               t.ten AS brand, d.ten AS category
        FROM giohang g
        JOIN sanpham s ON g.sanpham_id = s.id
        JOIN thuonghieu t ON s.thuonghieu_id = t.id
        JOIN danhmuc d ON s.danhmuc_id = d.id
        WHERE g.nguoidung_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$total = 0;
$discount = isset($_SESSION['coupon']['discount']) ? $_SESSION['coupon']['discount'] : 0;
$shipping = 50000; // Phí vận chuyển cố định

while ($row = $result->fetch_assoc()) {
    $row['quantity'] = $row['soluong'];
    $row['subtotal'] = $row['gia'] * $row['quantity'];
    $total += $row['subtotal'];
    $cartItems[] = $row;
}
$stmt->close();
?>

<!-- Breadcrumb -->
<div class="container mt-4">
    <nav class="breadcrumb-custom" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?option=home" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
        </ol>
    </nav>
</div>

<!-- Cart Content -->
<div class="container mb-5">
    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="cart-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title mb-0">Giỏ hàng của bạn</h2>
                    <span class="cart-count-text"><?php echo count($cartItems); ?> sản phẩm</span>
                </div>

                <?php if (empty($cartItems)): ?>
                    <!-- Empty Cart State -->
                    <div class="empty-cart-container">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3 class="empty-cart-title">Giỏ hàng của bạn đang trống</h3>
                        <p class="empty-cart-text">Hãy khám phá các sản phẩm tuyệt vời của chúng tôi và thêm vào giỏ hàng!</p>
                        <button class="btn-custom" onclick="location.href='?option=sanpham'">
                            <i class="fas fa-laptop me-2"></i>Khám phá sản phẩm
                        </button>
                    </div>
                <?php else: ?>
                    <?php foreach ($cartItems as $index => $item): ?>
                        <!-- Cart Item -->
                        <div class="cart-item" data-id="<?php echo $item['id']; ?>">
                            <div class="row align-items-center">
                                <div class="col-md-2 col-3">
                                    <div class="cart-item-image">
                                        <img src="<?php echo htmlspecialchars($item['hinhanh']); ?>" alt="<?php echo htmlspecialchars($item['ten']); ?>" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-4 col-9">
                                    <div class="cart-item-info">
                                        <h5 class="cart-item-title"><?php echo htmlspecialchars($item['ten']); ?></h5>
                                        <p class="cart-item-specs text-muted mb-2">
                                            <?php
                                            $thongso = json_decode($item['thongso'], true);
                                            if (is_array($thongso)) {
                                                echo "CPU: " . htmlspecialchars($thongso['cpu'] ?? 'N/A') . ", RAM: " . htmlspecialchars($thongso['ram'] ?? 'N/A') . 
                                                     ", Storage: " . htmlspecialchars($thongso['storage'] ?? 'N/A') . ", Screen: " . htmlspecialchars($thongso['screen'] ?? 'N/A');
                                            } else {
                                                echo "Thông số: N/A";
                                            }
                                            ?>
                                        </p>
                                        <div class="cart-item-meta">
                                            <span class="badge bg-<?php echo $item['soluongton'] > 0 ? 'success' : 'danger'; ?> me-2">
                                                <?php echo $item['soluongton'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?>
                                            </span>
                                            <small class="text-muted">SKU: <?php echo 'SP' . str_pad($item['id'], 4, '0', STR_PAD_LEFT); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12 mt-3 mt-md-0">
                                    <div class="quantity-container">
                                        <div class="quantity-selector-cart">
                                            <button class="quantity-btn-cart" onclick="updateQuantity(<?php echo $item['id']; ?>, 'decrease')">-</button>
                                            <input type="number" class="quantity-input-cart" value="<?php echo $item['quantity']; ?>" min="1" id="quantity-<?php echo $item['id']; ?>">
                                            <button class="quantity-btn-cart" onclick="updateQuantity(<?php echo $item['id']; ?>, 'increase')">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-6 mt-3 mt-md-0">
                                    <div class="cart-item-price">
                                        <div class="current-price-cart"><?php echo number_format($item['gia'], 0, ',', '.') . '₫'; ?></div>
                                        <?php if ($item['giacu']): ?>
                                            <div class="original-price-cart"><?php echo number_format($item['giacu'], 0, ',', '.') . '₫'; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-2 col-6 mt-3 mt-md-0 text-end">
                                    <div class="cart-item-actions">
                                        <button class="btn-favorite-cart" onclick="addToWishlist(<?php echo $item['id']; ?>)" title="Yêu thích">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                        <button class="btn-remove-cart" onclick="removeFromCart(<?php echo $item['id']; ?>)" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Cart Actions -->
                    <div class="cart-actions-bottom mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-outline-secondary w-100 mb-3 mb-md-0" onclick="clearCart()">
                                    <i class="fas fa-trash me-2"></i>Xóa tất cả
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-custom w-100" onclick="updateCart()">
                                    <i class="fas fa-sync-alt me-2"></i>Cập nhật giỏ hàng
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="order-summary-container">
                <div class="order-summary">
                    <h4 class="order-summary-title">Tóm tắt đơn hàng</h4>
                    
                    <div class="order-summary-item">
                        <span>Tạm tính (<span class="cart-count-text"><?php echo count($cartItems); ?></span> sản phẩm)</span>
                        <span class="subtotal"><?php echo number_format($total, 0, ',', '.') . '₫'; ?></span>
                    </div>
                    
                    <div class="order-summary-item">
                        <span>Giảm giá</span>
                        <span class="text-success discount"><?php echo number_format($discount, 0, ',', '.') . '₫'; ?></span>
                    </div>
                    
                    <div class="order-summary-item">
                        <span>Phí vận chuyển</span>
                        <span class="shipping"><?php echo $total > 0 ? number_format($shipping, 0, ',', '.') . '₫' : 'Miễn phí'; ?></span>
                    </div>
                    
                    <div class="order-summary-divider"></div>
                    
                    <div class="order-summary-total">
                        <span>Tổng cộng</span>
                        <span class="total"><?php echo number_format($total + $shipping - $discount, 0, ',', '.') . '₫'; ?></span>
                    </div>
                    
                    <?php if ($discount > 0): ?>
                        <div class="savings-highlight">
                            <i class="fas fa-tag me-2"></i>
                            Bạn tiết kiệm được <span class="discount"><?php echo number_format($discount, 0, ',', '.') . '₫'; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <button class="btn-checkout w-100 mt-4" onclick="proceedToCheckout()">
                        <i class="fas fa-credit-card me-2"></i>Tiến hành thanh toán
                    </button>
                    
                    <button class="btn btn-outline-primary w-100 mt-3" onclick="continueShopping()">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </button>
                </div>

                <!-- Coupon Section -->
                <div class="coupon-section mt-4">
                    <h5 class="coupon-title">Mã giảm giá</h5>
                    <div class="coupon-input-group">
                        <input type="text" class="form-control coupon-input" placeholder="Nhập mã giảm giá" id="couponCode">
                        <button class="btn-apply-coupon" onclick="applyCoupon()">Áp dụng</button>
                    </div>
                    <div class="available-coupons mt-3">
                        <small class="text-muted">Mã khuyến mãi có sẵn:</small>
                        <div class="coupon-tags mt-2">
                            <span class="coupon-tag" onclick="setCoupon('NEWCUSTOMER')">NEWCUSTOMER</span>
                            <span class="coupon-tag" onclick="setCoupon('SAVE100K')">SAVE100K</span>
                            <span class="coupon-tag" onclick="setCoupon('LAPTOP20')">LAPTOP20</span>
                        </div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="trust-badges mt-4">
                    <div class="trust-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Bảo hành chính hãng</span>
                    </div>
                    <div class="trust-item">
                        <i class="fas fa-truck"></i>
                        <span>Miễn phí vận chuyển</span>
                    </div>
                    <div class="trust-item">
                        <i class="fas fa-undo"></i>
                        <span>Đổi trả trong 7 ngày</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function numberFormat(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function updateCartSummary(data) {
    // Cập nhật số lượng sản phẩm
    document.querySelectorAll('.cart-count-text').forEach(el => {
        el.textContent = `${data.newCartCount} sản phẩm`;
    });

    // Cập nhật tạm tính
    document.querySelector('.subtotal').textContent = numberFormat(data.newSubtotal) + '₫';

    // Cập nhật giảm giá
    document.querySelectorAll('.discount').forEach(el => {
        el.textContent = numberFormat(data.discount) + '₫';
    });

    // Cập nhật phí vận chuyển
    document.querySelector('.shipping').textContent = data.newSubtotal > 0 ? numberFormat(50000) + '₫' : 'Miễn phí';

    // Cập nhật tổng tiền
    document.querySelector('.total').textContent = numberFormat(data.newTotal) + '₫';

    // Cập nhật badge giỏ hàng
    const cartCountBadge = document.querySelector('#cartCount');
    if (cartCountBadge) {
        cartCountBadge.textContent = data.newCartCount;
        cartCountBadge.style.display = data.newCartCount > 0 ? 'inline-block' : 'none';
    }

    // Cập nhật savings-highlight
    const savingsHighlight = document.querySelector('.savings-highlight');
    if (savingsHighlight) {
        if (data.discount > 0) {
            savingsHighlight.style.display = 'block';
        } else {
            savingsHighlight.style.display = 'none';
        }
    }
}

function updateQuantity(itemId, action) {
    const quantityInput = document.getElementById('quantity-' + itemId);
    let currentValue = parseInt(quantityInput.value);
    
    if (action === 'increase') {
        currentValue++;
    } else if (action === 'decrease' && currentValue > 1) {
        currentValue--;
    }
    
    fetch('controllers/cart_controller.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=update&product_id=${itemId}&quantity=${currentValue}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            quantityInput.value = currentValue;
            updateCartSummary(data);
            Swal.fire({
                icon: 'success',
                title: 'Đã cập nhật số lượng sản phẩm',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: data.message || 'Không thể cập nhật số lượng!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000
            });
        }
    });
}

function removeFromCart(itemId) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        fetch('controllers/cart_controller.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=remove&product_id=${itemId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
                cartItem.remove();
                updateCartSummary(data);
                if (data.newCartCount === 0) {
                    const cartContainer = document.querySelector('.cart-container');
                    cartContainer.innerHTML = `
                        <div class="empty-cart-container">
                            <div class="empty-cart-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h3 class="empty-cart-title">Giỏ hàng của bạn đang trống</h3>
                            <p class="empty-cart-text">Hãy khám phá các sản phẩm tuyệt vời của chúng tôi và thêm vào giỏ hàng!</p>
                            <button class="btn-custom" onclick="location.href='?option=sanpham'">
                                <i class="fas fa-laptop me-2"></i>Khám phá sản phẩm
                            </button>
                        </div>
                    `;
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Đã xóa sản phẩm khỏi giỏ hàng',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: data.message || 'Không thể xóa sản phẩm!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    }
}

function addToWishlist(itemId) {
    fetch('controllers/favorite_controller.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&product_id=${itemId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Đã thêm vào danh sách yêu thích',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000
            });
            event.target.closest('.btn-favorite-cart').style.background = 'var(--accent-color)';
            event.target.closest('.btn-favorite-cart').style.color = 'white';
        } else {
            Swal.fire({
                icon: 'warning',
                title: data.message || 'Không thể thêm vào yêu thích!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000
            });
        }
    });
}

function clearCart() {
    if (confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')) {
        fetch('controllers/cart_controller.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=clear'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartContainer = document.querySelector('.cart-container');
                cartContainer.innerHTML = `
                    <div class="empty-cart-container">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3 class="empty-cart-title">Giỏ hàng của bạn đang trống</h3>
                        <p class="empty-cart-text">Hãy khám phá các sản phẩm tuyệt vời của chúng tôi và thêm vào giỏ hàng!</p>
                        <button class="btn-custom" onclick="location.href='?option=sanpham'">
                            <i class="fas fa-laptop me-2"></i>Khám phá sản phẩm
                        </button>
                    </div>
                `;
                updateCartSummary(data);
                Swal.fire({
                    icon: 'success',
                    title: 'Đã xóa toàn bộ giỏ hàng',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: data.message || 'Không thể xóa giỏ hàng!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    }
}

function updateCart() {
    fetch('controllers/cart_controller.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=get_count'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartSummary(data);
            Swal.fire({
                icon: 'success',
                title: 'Đã cập nhật giỏ hàng',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: data.message || 'Không thể cập nhật giỏ hàng!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000
            });
        }
    });
}

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
                updateCartSummary(data);
                Swal.fire({
                    icon: 'success',
                    title: 'Đã áp dụng mã giảm giá: ' + couponCode,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: data.message || 'Mã giảm giá không hợp lệ!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Vui lòng nhập mã giảm giá',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1000
        });
    }
}

function setCoupon(code) {
    document.getElementById('couponCode').value = code;
    applyCoupon();
}

function proceedToCheckout() {
    Swal.fire({
        icon: 'success',
        title: 'Đang chuyển đến trang thanh toán...',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1000
    });
    setTimeout(() => {
        location.href = '?option=thanhtoan';
    }, 1000);
}

function continueShopping() {
    location.href = '?option=sanpham';
}

document.addEventListener('DOMContentLoaded', function() {
    // Load số lượng giỏ hàng
    fetch('controllers/cart_controller.php?action=get_count')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartSummary(data);
            }
        });
});
</script>