<?php
$userId = $_SESSION['id'];

// Lấy danh sách sản phẩm từ database
$query = "SELECT s.*, t.ten as thuonghieu, d.ten as danhmuc 
         FROM sanpham s 
         LEFT JOIN thuonghieu t ON s.thuonghieu_id = t.id 
         LEFT JOIN danhmuc d ON s.danhmuc_id = d.id 
         WHERE s.trangthai = 'hoatdong'";
$result = mysqli_query($conn, $query);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = [
        'id' => $row['id'],
        'name' => $row['ten'],
        'brand' => $row['thuonghieu'],
        'type' => $row['danhmuc'],
        'price' => $row['gia'],
        'originalPrice' => $row['giacu'],
        'image' => $row['hinhanh'],
        'badge' => $row['noibat'] ? 'hot' : ($row['giacu'] ? 'sale' : 'new'),
        'bestseller' => $row['noibat'],
        'description' => $row['mota']
    ];
}

// Lấy danh sách yêu thích
$favoriteIds = [];
$sqlFavorites = "SELECT sanpham_id FROM yeuthich WHERE nguoidung_id = ?";
$stmtFavorites = $conn->prepare($sqlFavorites);
$stmtFavorites->bind_param("i", $userId);
$stmtFavorites->execute();
$resultFavorites = $stmtFavorites->get_result();
while ($row = $resultFavorites->fetch_assoc()) {
    $favoriteIds[] = $row['sanpham_id'];
}
$stmtFavorites->close();

// Lấy danh sách thương hiệu
$brand_query = "SELECT ten FROM thuonghieu";
$brand_result = mysqli_query($conn, $brand_query);
$brands = [];
while ($row = mysqli_fetch_assoc($brand_result)) {
    $brands[] = $row['ten'];
}

// Lấy danh sách danh mục
$category_query = "SELECT ten FROM danhmuc";
$category_result = mysqli_query($conn, $category_query);
$categories = [];
while ($row = mysqli_fetch_assoc($category_result)) {
    $categories[] = $row['ten'];
}
?>

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
                            <button class="btn btn-custom btn-lg" onclick="window.location.href='?option=sanpham'">Khám phá ngay</button>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Business Laptop">
                    <div class="carousel-caption-overlay">
                        <div class="hero-content-overlay">
                            <h1 class="display-4 fw-bold mb-4">Laptop Văn Phòng</h1>
                            <p class="lead mb-4">Tối ưu cho công việc hàng ngày</p>
                            <button class="btn btn-custom btn-lg" onclick="window.location.href='?option=sanpham'">Xem bộ sưu tập</button>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1541807084-5c52b6b3adef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Creative Laptop">
                    <div class="carousel-caption-overlay">
                        <div class="hero-content-overlay">
                            <h1 class="display-4 fw-bold mb-4">Laptop Đồ Họa</h1>
                            <p class="lead mb-4">Sáng tạo không giới hạn</p>
                            <button class="btn btn-custom btn-lg" onclick="window.location.href='?option=sanpham'">Tìm hiểu thêm</button>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev" style="width: 5%;">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next" style="width: 5%;">
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
            <?php foreach ($brands as $brand): ?>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="category-card" onclick="filterByBrand('<?php echo htmlspecialchars($brand); ?>')">
                        <div class="category-icon"><i class="fas fa-laptop-code"></i></div>
                        <h5><?php echo htmlspecialchars($brand); ?></h5>
                    </div>
                </div>
            <?php endforeach; ?>
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
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?php echo htmlspecialchars($brand); ?>"><?php echo htmlspecialchars($brand); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Loại laptop:</label>
                    <select class="form-select" id="typeFilter" onchange="filterProducts()">
                        <option value="">Tất cả loại</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                        <?php endforeach; ?>
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

<script>
// Dữ liệu sản phẩm từ PHP
const laptops = <?php echo json_encode($products); ?>;
let filteredLaptops = [...laptops];
const favoriteIds = <?php echo json_encode($favoriteIds); ?>;

// Format price to Vietnamese currency
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

// Hiển thị thông báo SweetAlert2 dạng toast
function showToast(message, type = 'success') {
    Swal.fire({
        icon: type,
        title: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1000,
        timerProgressBar: true
    });
}

// Toggle favorite
function toggleFavorite(id) {
    fetch('controllers/favorite_controller.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=toggle&product_id=${id}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const btn = document.querySelector(`.favorite-btn[data-id="${id}"]`);
            const icon = btn.querySelector('i');
            if (data.action === 'added') {
                icon.classList.remove('far');
                icon.classList.add('fas');
                btn.classList.add('active');
                showToast('Đã thêm vào danh sách yêu thích!', 'success');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                btn.classList.remove('active');
                showToast('Đã xóa khỏi danh sách yêu thích!', 'warning');
            }
        } else {
            showToast(data.message || 'Có lỗi xảy ra!', 'warning');
        }
    })
    .catch(error => {
        showToast('Đã xảy ra lỗi hệ thống!', 'warning');
    });
}

// Add to cart
function addToCart(id) {
    fetch('controllers/cart_controller.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&product_id=${id}&quantity=1`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const cartCount = document.getElementById('cartCount');
            if (cartCount) {
                cartCount.textContent = data.newCartCount;
                cartCount.style.display = data.newCartCount > 0 ? 'inline-block' : 'none';
            } else if (data.newCartCount > 0) {
                const cartIcon = document.querySelector('.nav-link[href="?option=giohang"]');
                const badge = document.createElement('span');
                badge.id = 'cartCount';
                badge.className = 'badge bg-danger';
                badge.style.position = 'relative';
                badge.style.bottom = '15px';
                badge.textContent = data.newCartCount;
                cartIcon.appendChild(badge);
            }
            const laptop = laptops.find(l => l.id === id);
            if (laptop) {
                showToast(`Đã thêm "${laptop.name}" vào giỏ hàng!`, 'success');
            } else {
                showToast('Đã thêm sản phẩm vào giỏ hàng!', 'success');
            }
        } else {
            showToast(data.message || 'Không thể thêm vào giỏ hàng!', 'warning');
        }
    })
    .catch(error => {
        console.error('Lỗi chi tiết:', error); // Ghi log lỗi chi tiết
        showToast('Đã xảy ra lỗi: ' + error.message, 'warning');
    });
}

// Render products
function renderProducts(products) {
    const container = document.getElementById('productsContainer');
    container.innerHTML = '';

    products.forEach(laptop => {
        const badgeClass = laptop.badge === 'new' ? 'new' : laptop.badge === 'sale' ? 'sale' : 'hot';
        const badgeText = laptop.badge === 'new' ? 'MỚI' : laptop.badge === 'sale' ? 'GIẢM GIÁ' : 'HOT';
        const isFavorite = favoriteIds.includes(laptop.id);
        
        const productHTML = `
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="product-card position-relative">
                    <div class="position-relative">
                        <img src="${laptop.image}" alt="${laptop.name}" class="product-image">
                        <span class="product-badge ${badgeClass}">${badgeText}</span>
                        <button class="favorite-btn ${isFavorite ? 'active' : ''}" onclick="toggleFavorite(${laptop.id})" data-id="${laptop.id}">
                            <i class="${isFavorite ? 'fas' : 'far'} fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-3">
                        <h6 class="mb-2 text-truncate">${laptop.name}</h6>
                        <p class="text-muted small mb-2">${laptop.brand} | ${laptop.type}</p>
                        <div class="price mb-3">
                            <span class="current-price">${formatPrice(laptop.price)}</span>
                            ${laptop.originalPrice ? `<span class="original-price">${formatPrice(laptop.originalPrice)}</span>` : ''}
                        </div>
                        <div class="product-actions d-flex gap-2">
                            <button class="btn btn-custom flex-fill" onclick="addToCart(${laptop.id})">
                                <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                            </button>
                            <a href="?option=chitietsanpham&id=${laptop.id}" class="btn btn-outline-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += productHTML;
    });

    // Hiệu ứng hiển thị
    document.querySelectorAll('.product-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Filter products
function filterProducts() {
    const brandFilter = document.getElementById('brandFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;

    filteredLaptops = laptops.filter(laptop => {
        const matchBrand = !brandFilter || laptop.brand === brandFilter;
        const matchType = !typeFilter || laptop.type === typeFilter;
        return matchBrand && matchType;
    });

    sortProducts();
}

// Sort products
function sortProducts() {
    const sortOption = document.getElementById('priceSort').value;

    if (sortOption === 'low-high') {
        filteredLaptops.sort((a, b) => a.price - b.price);
    } else if (sortOption === 'high-low') {
        filteredLaptops.sort((a, b) => b.price - a.price);
    }

    renderProducts(filteredLaptops);
}

// Clear filters
function clearFilters() {
    document.getElementById('priceSort').value = '';
    document.getElementById('brandFilter').value = '';
    document.getElementById('typeFilter').value = '';
    filteredLaptops = [...laptops];
    renderProducts(filteredLaptops);
}

// Filter by brand from category card
function filterByBrand(brand) {
    document.getElementById('brandFilter').value = brand;
    filterProducts();
}

// Load products on page load
document.addEventListener('DOMContentLoaded', function() {
    renderProducts(filteredLaptops);

    // Load số lượng giỏ hàng
    fetch('controllers/cart_controller.php?action=get_count')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.newCartCount > 0) {
                const cartCount = document.getElementById('cartCount');
                if (cartCount) {
                    cartCount.textContent = data.newCartCount;
                    cartCount.style.display = 'inline-block';
                } else {
                    const cartIcon = document.querySelector('.nav-link[href="?option=giohang"]');
                    const badge = document.createElement('span');
                    badge.id = 'cartCount';
                    badge.className = 'badge bg-danger';
                    badge.style.position = 'relative';
                    badge.style.bottom = '15px';
                    badge.textContent = data.newCartCount;
                    cartIcon.appendChild(badge);
                }
            }
        })
        .catch(error => {
            console.error('Lỗi khi cập nhật số lượng giỏ hàng:', error);
        });
});
</script>