

    <!-- Hero Banner -->
    <section class="hero-banner-full">
        <div class="banner-carousel-full">
            <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Gaming Laptop">
                        <div class="carousel-caption-overlay">
                            <div class="hero-content-overlay">
                                <h1 class="display-4 fw-bold mb-4">Gaming Laptops</h1>
                                <p class="lead mb-4">Hiệu năng vượt trội cho game thủ chuyên nghiệp</p>
                                <button class="btn btn-custom btn-lg">Khám phá ngay</button>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Business Laptop">
                        <div class="carousel-caption-overlay">
                            <div class="hero-content-overlay">
                                <h1 class="display-4 fw-bold mb-4">Laptop Văn Phòng</h1>
                                <p class="lead mb-4">Tối ưu cho công việc hàng ngày</p>
                                <button class="btn btn-custom btn-lg">Xem bộ sưu tập</button>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1541807084-5c52b6b3adef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Creative Laptop">
                        <div class="carousel-caption-overlay">
                            <div class="hero-content-overlay">
                                <h1 class="display-4 fw-bold mb-4">Laptop Đồ Họa</h1>
                                <p class="lead mb-4">Sáng tạo không giới hạn</p>
                                <button class="btn btn-custom btn-lg">Tìm hiểu thêm</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title text-center">Danh Mục Sản Phẩm</h2>
            <div class="row">
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="category-card" onclick="filterByBrand('Dell')">
                        <div class="category-icon"><i class="fas fa-laptop-code"></i></div>
                        <h5>Dell</h5>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="category-card" onclick="filterByBrand('HP')">
                        <div class="category-icon"><i class="fas fa-desktop"></i></div>
                        <h5>HP</h5>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="category-card" onclick="filterByBrand('Asus')">
                        <div class="category-icon"><i class="fas fa-gamepad"></i></div>
                        <h5>Asus</h5>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="category-card" onclick="filterByBrand('Lenovo')">
                        <div class="category-icon"><i class="fas fa-briefcase"></i></div>
                        <h5>Lenovo</h5>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="category-card" onclick="filterByBrand('MSI')">
                        <div class="category-icon"><i class="fas fa-fire"></i></div>
                        <h5>MSI</h5>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="category-card" onclick="filterByBrand('Acer')">
                        <div class="category-icon"><i class="fas fa-rocket"></i></div>
                        <h5>Acer</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-3">
        <div class="container">
            <div class="filter-container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sắp xếp theo giá:</label>
                        <select class="form-select" id="priceSort" onchange="sortProducts()">
                            <option value="">Chọn sắp xếp</option>
                            <option value="low-high">Giá thấp đến cao</option>
                            <option value="high-low">Giá cao đến thấp</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Hãng:</label>
                        <select class="form-select" id="brandFilter" onchange="filterProducts()">
                            <option value="">Tất cả hãng</option>
                            <option value="Dell">Dell</option>
                            <option value="HP">HP</option>
                            <option value="Asus">Asus</option>
                            <option value="Lenovo">Lenovo</option>
                            <option value="MSI">MSI</option>
                            <option value="Acer">Acer</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Loại laptop:</label>
                        <select class="form-select" id="typeFilter" onchange="filterProducts()">
                            <option value="">Tất cả loại</option>
                            <option value="gaming">Gaming</option>
                            <option value="office">Văn phòng</option>
                            <option value="graphics">Đồ họa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-custom w-100" onclick="clearFilters()">
                            <i class="fas fa-refresh"></i> Xóa bộ lọc
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title text-center">Sản Phẩm Nổi Bật</h2>
            <div class="row" id="productsContainer">
                <!-- Products will be loaded here by JavaScript -->
            </div>
        </div>
    </section>

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

    <script>
        // Sample data
        const laptops = [
            {
                id: 1,
                name: "Dell XPS 13 Plus",
                brand: "Dell",
                type: "office",
                price: 32990000,
                originalPrice: 35990000,
                image: "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                badge: "sale",
                bestseller: true,
                description: "Laptop cao cấp với thiết kế tinh tế"
            },
            {
                id: 2,
                name: "HP Omen 16 Gaming",
                brand: "HP",
                type: "gaming",
                price: 28990000,
                originalPrice: null,
                image: "https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                badge: "new",
                bestseller: true,
                description: "Gaming laptop hiệu năng cao"
            },
            {
                id: 3,
                name: "Asus ROG Strix G15",
                brand: "Asus",
                type: "gaming",
                price: 35990000,
                originalPrice: null,
                image: "https://images.unsplash.com/photo-1541807084-5c52b6b3adef?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                badge: "hot",
                bestseller: true,
                description: "Laptop gaming chuyên nghiệp"
            },
            {
                id: 4,
                name: "Lenovo ThinkPad X1 Carbon",
                brand: "Lenovo",
                type: "office",
                price: 45990000,
                originalPrice: 49990000,
                image: "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                badge: "sale",
                bestseller: false,
                description: "Laptop doanh nhân cao cấp"
            },
            {
                id: 5,
                name: "MSI Creator Z16",
                brand: "MSI",
                type: "graphics",
                price: 52990000,
                originalPrice: null,
                image: "https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                badge: "new",
                bestseller: true,
                description: "Laptop sáng tạo nội dung"
            },
            {
                id: 6,
                name: "Acer Nitro 5",
                brand: "Acer",
                type: "gaming",
                price: 18990000,
                originalPrice: 21990000,
                image: "https://images.unsplash.com/photo-1541807084-5c52b6b3adef?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                badge: "sale",
                bestseller: false,
                description: "Gaming laptop giá tốt"
            },
            {
                id: 7,
                name: "Dell Inspiron 15 3000",
                brand: "Dell",
                type: "office",
                price: 15990000,
                originalPrice: null,
                image: "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                badge: "hot",
                bestseller: false,
                description: "Laptop học tập văn phòng"
            },
            {
                id: 8,
                name: "HP ZBook Studio",
                brand: "HP",
                type: "graphics",
                price: 65990000,
                originalPrice: null,
                image: "https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                badge: "new",
                bestseller: true,
                description: "Workstation chuyên nghiệp"
            }
        ];

        let filteredLaptops = [...laptops];
        let favorites = [];
        let cart = [];

        // Format price to Vietnamese currency
        function formatPrice(price) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(price);
        }

        // Show toast notification
        function showToast(message) {
            document.getElementById('toastMessage').textContent = message;
            const toast = new bootstrap.Toast(document.getElementById('successToast'));
            toast.show();
        }

        // Toggle favorite
        function toggleFavorite(id) {
            const index = favorites.indexOf(id);
            if (index > -1) {
                favorites.splice(index, 1);
                showToast('Đã xóa khỏi danh sách yêu thích!');
            } else {
                favorites.push(id);
                showToast('Đã thêm vào danh sách yêu thích!');
            }
            updateFavoriteIcons();
        }

        // Update favorite icons
        function updateFavoriteIcons() {
            document.querySelectorAll('.favorite-btn').forEach(btn => {
                const productId = parseInt(btn.getAttribute('data-id'));
                const icon = btn.querySelector('i');
                if (favorites.includes(productId)) {
                    icon.className = 'fas fa-heart';
                    btn.classList.add('active');
                } else {
                    icon.className = 'far fa-heart';
                    btn.classList.remove('active');
                }
            });
        }

        // Add to cart
        function addToCart(id) {
            const laptop = laptops.find(l => l.id === id);
            const existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({...laptop, quantity: 1});
            }
            
            updateCartCount();
            showToast(`Đã thêm "${laptop.name}" vào giỏ hàng!`);
        }

        // Update cart count
        function updateCartCount() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').textContent = totalItems;
        }

        // Show cart (placeholder)
        function showCart() {
            if (cart.length === 0) {
                alert('Giỏ hàng của bạn đang trống!');
                return;
            }
            
            let cartContent = 'Giỏ hàng của bạn:\n\n';
            let total = 0;
            
            cart.forEach(item => {
                cartContent += `${item.name} - Số lượng: ${item.quantity} - ${formatPrice(item.price)}\n`;
                total += item.price * item.quantity;
            });
            
            cartContent += `\nTổng cộng: ${formatPrice(total)}`;
            alert(cartContent);
        }

        // Render products
        function renderProducts(products) {
            const container = document.getElementById('productsContainer');
            container.innerHTML = '';

            products.forEach(laptop => {
                const badgeClass = laptop.badge === 'new' ? 'new' : laptop.badge === 'sale' ? 'sale' : '';
                const badgeText = laptop.badge === 'new' ? 'MỚI' : laptop.badge === 'sale' ? 'GIẢM GIÁ' : 'HOT';
                const isFavorite = favorites.includes(laptop.id);
                
                const productHTML = `
                    <div class="col-lg-3 col-md-6">
                        <div class="product-card">
                            <div class="position-relative">
                                <img src="${laptop.image}" alt="${laptop.name}" class="product-image">
                                <span class="product-badge ${badgeClass}">${badgeText}</span>
                                <button class="favorite-btn ${isFavorite ? 'active' : ''}" onclick="toggleFavorite(${laptop.id})" data-id="${laptop.id}">
                                    <i class="${isFavorite ? 'fas' : 'far'} fa-heart"></i>
                                </button>
                            </div>
                            <div class="p-3">
                                <h6 class="mb-2">${laptop.name}</h6>
                                <p class="text-muted small mb-2">${laptop.description}</p>
                                <div class="price mb-3">
                                    ${laptop.originalPrice ? `<span class="old-price">${formatPrice(laptop.originalPrice)}</span>` : ''}
                                    ${formatPrice(laptop.price)}
                                </div>
                                <div class="product-actions">
                                    <button class="btn btn-custom flex-fill me-2" onclick="window.location.href='?option=chitietsanpham&id=${laptop.id}'">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </button>
                                    <button class="btn btn-success flex-fill" onclick="addToCart(${laptop.id})">
                                        <i class="fas fa-shopping-cart"></i> Thêm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += productHTML;
            });
        }

        // Search functionality with autocomplete
        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            const suggestions = document.getElementById('autocompleteSuggestions');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                
                if (query.length < 2) {
                    suggestions.style.display = 'none';
                    return;
                }

                const matches = laptops.filter(laptop => 
                    laptop.name.toLowerCase().includes(query) ||
                    laptop.brand.toLowerCase().includes(query) ||
                    laptop.type.toLowerCase().includes(query)
                );

                if (matches.length > 0) {
                    suggestions.innerHTML = matches.slice(0, 5).map(laptop => 
                        `<div class="autocomplete-suggestion" onclick="selectSuggestion('${laptop.name}')">${laptop.name}</div>`
                    ).join('');
                    suggestions.style.display = 'block';
                } else {
                    suggestions.style.display = 'none';
                }
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestions.contains(e.target)) {
                    suggestions.style.display = 'none';
                }
            });
        }

        function selectSuggestion(name) {
            document.getElementById('searchInput').value = name;
            document.getElementById('autocompleteSuggestions').style.display = 'none';
            performSearch();
        }

        function performSearch() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            if (!query) {
                filteredLaptops = [...laptops];
            } else {
                filteredLaptops = laptops.filter(laptop => 
                    laptop.name.toLowerCase().includes(query) ||
                    laptop.brand.toLowerCase().includes(query) ||
                    laptop.type.toLowerCase().includes(query)
                );
            }
            renderProducts(filteredLaptops);
        }

        // Filter functions
        function filterByBrand(brand) {
            document.getElementById('brandFilter').value = brand;
            filterProducts();
        }

        function filterProducts() {
            const brandFilter = document.getElementById('brandFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;

            filteredLaptops = laptops.filter(laptop => {
                const brandMatch = !brandFilter || laptop.brand === brandFilter;
                const typeMatch = !typeFilter || laptop.type === typeFilter;
                return brandMatch && typeMatch;
            });

            renderProducts(filteredLaptops);
        }

        function sortProducts() {
            const sortValue = document.getElementById('priceSort').value;
            
            if (sortValue === 'low-high') {
                filteredLaptops.sort((a, b) => a.price - b.price);
            } else if (sortValue === 'high-low') {
                filteredLaptops.sort((a, b) => b.price - a.price);
            }

            renderProducts(filteredLaptops);
        }

        function clearFilters() {
            document.getElementById('brandFilter').value = '';
            document.getElementById('typeFilter').value = '';
            document.getElementById('priceSort').value = '';
            document.getElementById('searchInput').value = '';
            filteredLaptops = [...laptops];
            renderProducts(filteredLaptops);
        }

        function viewProduct(id) {
            const laptop = laptops.find(l => l.id === id);
            alert(`Xem chi tiết: ${laptop.name}\nGiá: ${formatPrice(laptop.price)}\n${laptop.description}`);
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Show bestsellers first
            const bestsellers = laptops.filter(laptop => laptop.bestseller);
            renderProducts(bestsellers);
            setupSearch();
        });

        // Show all products after 3 seconds
        setTimeout(() => {
            renderProducts(laptops);
        }, 3000);
    </script>
