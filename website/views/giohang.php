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
                    <span class="cart-count-text">3 sản phẩm</span>
                </div>

                <!-- Cart Item 1 -->
                <div class="cart-item">
                    <div class="row align-items-center">
                        <div class="col-md-2 col-3">
                            <div class="cart-item-image">
                                <img src="https://via.placeholder.com/150x150/667eea/ffffff?text=Laptop" alt="Dell XPS 13" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-md-4 col-9">
                            <div class="cart-item-info">
                                <h5 class="cart-item-title">Dell XPS 13 Plus</h5>
                                <p class="cart-item-specs text-muted mb-2">Intel Core i7-12700H, 16GB RAM, 512GB SSD</p>
                                <div class="cart-item-meta">
                                    <span class="badge bg-success me-2">Còn hàng</span>
                                    <small class="text-muted">SKU: DX13P-001</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-12 mt-3 mt-md-0">
                            <div class="quantity-container">
                                <div class="quantity-selector-cart">
                                    <button class="quantity-btn-cart" onclick="updateQuantity(1, 'decrease')">-</button>
                                    <input type="number" class="quantity-input-cart" value="1" min="1" id="quantity-1">
                                    <button class="quantity-btn-cart" onclick="updateQuantity(1, 'increase')">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mt-3 mt-md-0">
                            <div class="cart-item-price">
                                <div class="current-price-cart">28.990.000₫</div>
                                <div class="original-price-cart">32.000.000₫</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mt-3 mt-md-0 text-end">
                            <div class="cart-item-actions">
                                <button class="btn-favorite-cart" onclick="addToWishlist(1)" title="Yêu thích">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn-remove-cart" onclick="removeFromCart(1)" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Item 2 -->
                <div class="cart-item">
                    <div class="row align-items-center">
                        <div class="col-md-2 col-3">
                            <div class="cart-item-image">
                                <img src="https://via.placeholder.com/150x150/764ba2/ffffff?text=Gaming" alt="HP Omen 15" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-md-4 col-9">
                            <div class="cart-item-info">
                                <h5 class="cart-item-title">HP Omen 15 Gaming</h5>
                                <p class="cart-item-specs text-muted mb-2">AMD Ryzen 7 5800H, RTX 3060, 16GB RAM, 512GB SSD</p>
                                <div class="cart-item-meta">
                                    <span class="badge bg-warning me-2">Sắp hết hàng</span>
                                    <small class="text-muted">SKU: HPO15-002</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-12 mt-3 mt-md-0">
                            <div class="quantity-container">
                                <div class="quantity-selector-cart">
                                    <button class="quantity-btn-cart" onclick="updateQuantity(2, 'decrease')">-</button>
                                    <input type="number" class="quantity-input-cart" value="2" min="1" id="quantity-2">
                                    <button class="quantity-btn-cart" onclick="updateQuantity(2, 'increase')">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mt-3 mt-md-0">
                            <div class="cart-item-price">
                                <div class="current-price-cart">24.500.000₫</div>
                                <div class="original-price-cart">26.990.000₫</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mt-3 mt-md-0 text-end">
                            <div class="cart-item-actions">
                                <button class="btn-favorite-cart" onclick="addToWishlist(2)" title="Yêu thích">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn-remove-cart" onclick="removeFromCart(2)" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Item 3 -->
                <div class="cart-item">
                    <div class="row align-items-center">
                        <div class="col-md-2 col-3">
                            <div class="cart-item-image">
                                <img src="https://via.placeholder.com/150x150/3498db/ffffff?text=MacBook" alt="MacBook Air M2" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-md-4 col-9">
                            <div class="cart-item-info">
                                <h5 class="cart-item-title">MacBook Air M2</h5>
                                <p class="cart-item-specs text-muted mb-2">Apple M2 Chip, 8GB RAM, 256GB SSD</p>
                                <div class="cart-item-meta">
                                    <span class="badge bg-success me-2">Còn hàng</span>
                                    <small class="text-muted">SKU: MBA-M2-001</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-12 mt-3 mt-md-0">
                            <div class="quantity-container">
                                <div class="quantity-selector-cart">
                                    <button class="quantity-btn-cart" onclick="updateQuantity(3, 'decrease')">-</button>
                                    <input type="number" class="quantity-input-cart" value="1" min="1" id="quantity-3">
                                    <button class="quantity-btn-cart" onclick="updateQuantity(3, 'increase')">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mt-3 mt-md-0">
                            <div class="cart-item-price">
                                <div class="current-price-cart">27.990.000₫</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mt-3 mt-md-0 text-end">
                            <div class="cart-item-actions">
                                <button class="btn-favorite-cart" onclick="addToWishlist(3)" title="Yêu thích">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn-remove-cart" onclick="removeFromCart(3)" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

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
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="order-summary-container">
                <div class="order-summary">
                    <h4 class="order-summary-title">Tóm tắt đơn hàng</h4>
                    
                    <div class="order-summary-item">
                        <span>Tạm tính (4 sản phẩm)</span>
                        <span>102.480.000₫</span>
                    </div>
                    
                    <div class="order-summary-item">
                        <span>Giảm giá</span>
                        <span class="text-success">-3.500.000₫</span>
                    </div>
                    
                    <div class="order-summary-item">
                        <span>Phí vận chuyển</span>
                        <span>Miễn phí</span>
                    </div>
                    
                    <div class="order-summary-divider"></div>
                    
                    <div class="order-summary-total">
                        <span>Tổng cộng</span>
                        <span>98.980.000₫</span>
                    </div>
                    
                    <div class="savings-highlight">
                        <i class="fas fa-tag me-2"></i>
                        Bạn tiết kiệm được 3.500.000₫
                    </div>
                    
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
                            <span class="coupon-tag" onclick="setCoupon('SAVE500K')">SAVE500K</span>
                            <span class="coupon-tag" onclick="setCoupon('NEWUSER10')">NEWUSER10</span>
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

<!-- Empty Cart State (Hidden by default) -->
<div class="container mb-5 d-none" id="emptyCartState">
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
</div>


<script>
function updateQuantity(itemId, action) {
    const quantityInput = document.getElementById('quantity-' + itemId);
    let currentValue = parseInt(quantityInput.value);
    
    if (action === 'increase') {
        quantityInput.value = currentValue + 1;
    } else if (action === 'decrease' && currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
    
    // Update total price (would connect to backend)
    console.log('Updated quantity for item ' + itemId + ' to ' + quantityInput.value);
    
    // Show success toast
    showToast('Đã cập nhật số lượng sản phẩm');
}

function removeFromCart(itemId) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        // Would connect to backend to remove item
        console.log('Removing item ' + itemId + ' from cart');
        
        // For demo, hide the cart item
        const cartItems = document.querySelectorAll('.cart-item');
        if (cartItems[itemId - 1]) {
            cartItems[itemId - 1].style.display = 'none';
        }
        
        showToast('Đã xóa sản phẩm khỏi giỏ hàng');
        
        // Check if cart is empty and show empty state
        const visibleItems = document.querySelectorAll('.cart-item:not([style*="display: none"])');
        if (visibleItems.length === 0) {
            document.querySelector('.cart-container').parentElement.parentElement.style.display = 'none';
            document.getElementById('emptyCartState').classList.remove('d-none');
        }
    }
}

function addToWishlist(itemId) {
    // Would connect to backend to add to wishlist
    console.log('Adding item ' + itemId + ' to wishlist');
    showToast('Đã thêm vào danh sách yêu thích');
    
    // Update button state
    const btn = event.target.closest('.btn-favorite-cart');
    btn.style.background = 'var(--accent-color)';
    btn.style.color = 'white';
}

function clearCart() {
    if (confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')) {
        // Would connect to backend to clear cart
        console.log('Clearing entire cart');
        
        document.querySelector('.cart-container').parentElement.parentElement.style.display = 'none';
        document.getElementById('emptyCartState').classList.remove('d-none');
        
        showToast('Đã xóa toàn bộ giỏ hàng');
    }
}

function updateCart() {
    // Would connect to backend to update cart
    console.log('Updating cart');
    showToast('Đã cập nhật giỏ hàng');
}

function applyCoupon() {
    const couponCode = document.getElementById('couponCode').value.trim();
    if (couponCode) {
        // Would connect to backend to validate and apply coupon
        console.log('Applying coupon: ' + couponCode);
        showToast('Đã áp dụng mã giảm giá: ' + couponCode);
    } else {
        alert('Vui lòng nhập mã giảm giá');
    }
}

function setCoupon(code) {
    document.getElementById('couponCode').value = code;
    applyCoupon();
}

function proceedToCheckout() {
    // Would redirect to checkout page
    console.log('Proceeding to checkout');
    showToast('Đang chuyển đến trang thanh toán...');
    location.href = '?option=thanhtoan';
}

function continueShopping() {
    location.href = '?option=sanpham';
}

function showToast(message) {
    const toastElement = document.getElementById('successToast');
    const toastMessage = document.getElementById('toastMessage');
    toastMessage.textContent = message;
    
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}
</script>