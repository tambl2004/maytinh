
    <!-- Breadcrumb -->
    <div class="container mt-4">
        <nav class="breadcrumb-custom">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="giaodien.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="#">Laptop</a></li>
                <li class="breadcrumb-item"><a href="#">Dell</a></li>
                <li class="breadcrumb-item active">Dell XPS 13 Plus</li>
            </ol>
        </nav>
    </div>

    <!-- Product Detail -->
    <div class="container">
        <div class="product-detail-container">
            <div class="row g-0">
                <!-- Product Gallery -->
                <div class="col-lg-6">
                    <div class="product-gallery">
                        <div class="product-badge-large sale">GIẢM GIÁ</div>
                        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Dell XPS 13 Plus" class="main-image" id="mainImage">
                        
                        <div class="thumbnail-container">
                            <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" 
                                 alt="Dell XPS 13 Plus" class="thumbnail active" onclick="changeImage(this.src)">
                            <img src="https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" 
                                 alt="Dell XPS 13 Plus" class="thumbnail" onclick="changeImage(this.src)">
                            <img src="https://images.unsplash.com/photo-1541807084-5c52b6b3adef?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" 
                                 alt="Dell XPS 13 Plus" class="thumbnail" onclick="changeImage(this.src)">
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="product-info">
                        <div class="rating-stars mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="ms-2 text-muted">(128 đánh giá)</span>
                        </div>

                        <h1 class="h2 mb-3">Dell XPS 13 Plus (9320)</h1>
                        <p class="text-muted mb-4">Laptop cao cấp với thiết kế tinh tế, hiệu năng mạnh mẽ và màn hình InfinityEdge đẹp mắt. Hoàn hảo cho công việc văn phòng và sáng tạo nội dung.</p>

                        <div class="price-section">
                            <div class="d-flex align-items-center flex-wrap">
                                <span class="current-price">32.990.000₫</span>
                                <span class="original-price">35.990.000₫</span>
                                <span class="discount-percent">-8%</span>
                            </div>
                            <small class="text-muted">Đã bao gồm VAT</small>
                        </div>

                        <!-- Product Options -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Cấu hình:</label>
                            <div class="btn-group d-block" role="group">
                                <input type="radio" class="btn-check" name="config" id="config1" checked>
                                <label class="btn btn-outline-primary me-2 mb-2" for="config1">i7-1280P | 16GB | 512GB SSD</label>
                                
                                <input type="radio" class="btn-check" name="config" id="config2">
                                <label class="btn btn-outline-primary me-2 mb-2" for="config2">i7-1280P | 32GB | 1TB SSD (+5tr)</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Màu sắc:</label>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="color" id="silver" checked>
                                <label class="btn btn-outline-secondary me-2" for="silver">Bạc</label>
                                
                                <input type="radio" class="btn-check" name="color" id="black">
                                <label class="btn btn-outline-dark" for="black">Đen</label>
                            </div>
                        </div>

                        <!-- Quantity and Actions -->
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <label class="form-label fw-bold">Số lượng:</label>
                                <div class="quantity-selector">
                                    <button class="quantity-btn" onclick="decreaseQuantity()">-</button>
                                    <input type="number" class="quantity-input" id="quantity" value="1" min="1" max="10">
                                    <button class="quantity-btn" onclick="increaseQuantity()">+</button>
                                </div>
                            </div>
                            <div class="col-auto">
                                <span class="text-success"><i class="fas fa-check-circle"></i> Còn 15 sản phẩm</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex action-buttons mb-4">
                            <button class="btn btn-add-cart" onclick="addToCart(1)">
                                <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ hàng
                            </button>
                            <button class="btn btn-buy-now" onclick="buyNow()">
                                <i class="fas fa-bolt me-2"></i>Mua ngay
                            </button>
                            <button class="favorite-btn-large" onclick="toggleFavorite(1)" id="favoriteBtn">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>

                        <!-- Additional Info -->
                        <div class="border-top pt-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <i class="fas fa-shipping-fast text-primary mb-2 d-block"></i>
                                    <small>Miễn phí vận chuyển</small>
                                </div>
                                <div class="col-4">
                                    <i class="fas fa-shield-alt text-success mb-2 d-block"></i>
                                    <small>Bảo hành 24 tháng</small>
                                </div>
                                <div class="col-4">
                                    <i class="fas fa-undo text-warning mb-2 d-block"></i>
                                    <small>Đổi trả 15 ngày</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="container mb-5">
        <ul class="nav nav-tabs nav-tabs-custom" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button">Thông số kỹ thuật</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">Mô tả chi tiết</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">Đánh giá (128)</button>
            </li>
        </ul>
        
        <div class="tab-content tab-content-custom" id="productTabContent">
            <div class="tab-pane fade show active" id="specs" role="tabpanel">
                <div class="specs-table">
                    <table class="table mb-0">
                        <tr>
                            <th width="30%">Bộ vi xử lý</th>
                            <td>Intel Core i7-1280P (12 nhân, 20 luồng, 4.8GHz)</td>
                        </tr>
                        <tr>
                            <th>RAM</th>
                            <td>16GB LPDDR5 4800MHz (Onboard)</td>
                        </tr>
                        <tr>
                            <th>Ổ cứng</th>
                            <td>512GB PCIe NVMe SSD</td>
                        </tr>
                        <tr>
                            <th>Card đồ họa</th>
                            <td>Intel Iris Xe Graphics</td>
                        </tr>
                        <tr>
                            <th>Màn hình</th>
                            <td>13.4" 3.5K (3456x2160) OLED Touch, 400 nits</td>
                        </tr>
                        <tr>
                            <th>Hệ điều hành</th>
                            <td>Windows 11 Home</td>
                        </tr>
                        <tr>
                            <th>Cổng kết nối</th>
                            <td>2x Thunderbolt 4, 1x Audio Jack 3.5mm</td>
                        </tr>
                        <tr>
                            <th>Kết nối không dây</th>
                            <td>Wi-Fi 6E, Bluetooth 5.2</td>
                        </tr>
                        <tr>
                            <th>Pin</th>
                            <td>55WHr, sạc nhanh 65W</td>
                        </tr>
                        <tr>
                            <th>Kích thước</th>
                            <td>295.3 x 199.04 x 15.28mm</td>
                        </tr>
                        <tr>
                            <th>Trọng lượng</th>
                            <td>1.26kg</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="tab-pane fade" id="description" role="tabpanel">
                <h4>Thiết kế tinh tế, hiệu năng vượt trội</h4>
                <p>Dell XPS 13 Plus là biểu tượng của sự hoàn hảo trong thiết kế laptop cao cấp. Với vỏ nhôm nguyên khối được gia công CNC chính xác, máy mang đến cảm giác cứng cáp và sang trọng trong từng chi tiết.</p>
                
                <h5>Màn hình OLED đỉnh cao</h5>
                <p>Màn hình 13.4 inch 3.5K OLED mang đến trải nghiệm hình ảnh tuyệt vời với màu sắc sống động, độ tương phản vô cực và độ sáng 400 nits. Công nghệ cảm ứng đa điểm hỗ trợ thao tác trực quan.</p>
                
                <h5>Hiệu năng Intel thế hệ 12</h5>
                <p>Bộ vi xử lý Intel Core i7-1280P với 12 nhân 20 luồng mang đến hiệu năng xử lý mạnh mẽ cho mọi tác vụ từ văn phòng đến sáng tạo nội dung chuyên nghiệp.</p>
                
                <h5>Bàn phím và touchpad cải tiến</h5>
                <p>Bàn phím capacitive với đèn nền LED và touchpad haptic mang đến trải nghiệm gõ phím và điều khiển hoàn toàn mới, mượt mà và chính xác.</p>
            </div>
            
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <div class="display-4 fw-bold text-warning">4.6</div>
                            <div class="rating-stars mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <p class="text-muted">128 đánh giá</p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">5 sao</span>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: 65%"></div>
                                </div>
                                <span class="text-muted">83</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">4 sao</span>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: 25%"></div>
                                </div>
                                <span class="text-muted">32</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">3 sao</span>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: 8%"></div>
                                </div>
                                <span class="text-muted">10</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2">2 sao</span>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: 2%"></div>
                                </div>
                                <span class="text-muted">2</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="me-2">1 sao</span>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: 1%"></div>
                                </div>
                                <span class="text-muted">1</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <!-- Sample Reviews -->
                <div class="mb-4">
                    <div class="d-flex mb-2">
                        <strong>Nguyễn Văn A</strong>
                        <div class="rating-stars ms-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-muted ms-auto">15/01/2025</span>
                    </div>
                    <p>Laptop rất đẹp, hiệu năng mạnh, màn hình OLED tuyệt vời. Rất hài lòng với sản phẩm này.</p>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex mb-2">
                        <strong>Trần Thị B</strong>
                        <div class="rating-stars ms-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <span class="text-muted ms-auto">12/01/2025</span>
                    </div>
                    <p>Máy đẹp, cấu hình tốt nhưng pin hơi yếu so với mong đợi. Tổng thể vẫn hài lòng.</p>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex mb-2">
                        <strong>Lê Minh C</strong>
                        <div class="rating-stars ms-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-muted ms-auto">08/01/2025</span>
                    </div>
                    <p>Thiết kế sang trọng, màn hình OLED cực đẹp. Phù hợp cho công việc thiết kế đồ họa. Recommend!</p>
                </div>
                
                <button class="btn btn-outline-primary">Xem thêm đánh giá</button>
            </div>
        </div>
    </div>



    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="cartItems">
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Giỏ hàng của bạn đang trống</p>
                            <a href="giaodien.php" class="btn btn-primary">Tiếp tục mua sắm</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <strong>Tổng cộng: <span id="cartTotal">0₫</span></strong>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" onclick="checkout()">Thanh toán</button>
                            <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Tiếp tục mua sắm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Cart functionality
        let cart = [];
        let favorites = [];

        // Product data
        const product = {
            id: 1,
            name: "Dell XPS 13 Plus (9320)",
            price: 32990000,
            originalPrice: 35990000,
            image: "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
        };

        function changeImage(src) {
            document.getElementById('mainImage').src = src;
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            if (currentValue < 10) {
                quantityInput.value = currentValue + 1;
            }
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        }

        function addToCart(productId) {
            const quantity = parseInt(document.getElementById('quantity').value);
            const config = document.querySelector('input[name="config"]:checked').nextElementSibling.textContent;
            const color = document.querySelector('input[name="color"]:checked').nextElementSibling.textContent;
            
            const cartItem = {
                ...product,
                quantity: quantity,
                config: config,
                color: color,
                total: product.price * quantity
            };
            
            // Check if item already exists in cart
            const existingItemIndex = cart.findIndex(item => 
                item.id === productId && item.config === config && item.color === color
            );
            
            if (existingItemIndex > -1) {
                cart[existingItemIndex].quantity += quantity;
                cart[existingItemIndex].total = cart[existingItemIndex].price * cart[existingItemIndex].quantity;
            } else {
                cart.push(cartItem);
            }
            
            updateCartDisplay();
            showNotification('Đã thêm sản phẩm vào giỏ hàng!', 'success');
        }

        function buyNow() {
            addToCart(1);
            setTimeout(() => {
                window.location.href = 'checkout.php';
            }, 500);
        }

        function toggleFavorite(productId) {
            const favoriteBtn = document.getElementById('favoriteBtn');
            const icon = favoriteBtn.querySelector('i');
            
            if (favorites.includes(productId)) {
                favorites = favorites.filter(id => id !== productId);
                icon.className = 'far fa-heart';
                favoriteBtn.classList.remove('active');
                showNotification('Đã xóa khỏi danh sách yêu thích', 'info');
            } else {
                favorites.push(productId);
                icon.className = 'fas fa-heart';
                favoriteBtn.classList.add('active');
                showNotification('Đã thêm vào danh sách yêu thích!', 'success');
            }
        }

        function showCart() {
            updateCartModal();
            const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
            cartModal.show();
        }

        function updateCartDisplay() {
            const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
            document.getElementById('cartCount').textContent = cartCount;
        }

        function updateCartModal() {
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');
            
            if (cart.length === 0) {
                cartItems.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Giỏ hàng của bạn đang trống</p>
                        <a href="giaodien.php" class="btn btn-primary">Tiếp tục mua sắm</a>
                    </div>
                `;
                cartTotal.textContent = '0₫';
                return;
            }
            
            let itemsHTML = '';
            let total = 0;
            
            cart.forEach((item, index) => {
                total += item.total;
                itemsHTML += `
                    <div class="cart-item d-flex align-items-center mb-3 p-3 border rounded">
                        <img src="${item.image}" alt="${item.name}" class="me-3" style="width: 80px; height: 80px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${item.name}</h6>
                            <small class="text-muted">${item.config} - ${item.color}</small>
                            <div class="d-flex align-items-center mt-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="updateCartQuantity(${index}, -1)">-</button>
                                <span class="mx-3">${item.quantity}</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="updateCartQuantity(${index}, 1)">+</button>
                                <button class="btn btn-sm btn-outline-danger ms-3" onclick="removeFromCart(${index})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">${item.total.toLocaleString('vi-VN')}₫</div>
                        </div>
                    </div>
                `;
            });
            
            cartItems.innerHTML = itemsHTML;
            cartTotal.textContent = total.toLocaleString('vi-VN') + '₫';
        }

        function updateCartQuantity(index, change) {
            cart[index].quantity += change;
            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            } else {
                cart[index].total = cart[index].price * cart[index].quantity;
            }
            updateCartDisplay();
            updateCartModal();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartDisplay();
            updateCartModal();
            showNotification('Đã xóa sản phẩm khỏi giỏ hàng', 'info');
        }

        function checkout() {
            if (cart.length === 0) {
                showNotification('Giỏ hàng của bạn đang trống', 'warning');
                return;
            }
            
            // Save cart to localStorage for checkout page
            localStorage.setItem('cart', JSON.stringify(cart));
            window.location.href = 'checkout.php';
        }

        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Load cart from localStorage if exists
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                cart = JSON.parse(savedCart);
                updateCartDisplay();
            }
            
            // Load favorites from localStorage if exists
            const savedFavorites = localStorage.getItem('favorites');
            if (savedFavorites) {
                favorites = JSON.parse(savedFavorites);
                if (favorites.includes(1)) {
                    toggleFavorite(1);
                }
            }
        });

        // Save cart and favorites to localStorage when page unloads
        window.addEventListener('beforeunload', function() {
            localStorage.setItem('cart', JSON.stringify(cart));
            localStorage.setItem('favorites', JSON.stringify(favorites));
        });
    </script>
