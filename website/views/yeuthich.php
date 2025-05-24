<?php
// Giả lập dữ liệu sản phẩm yêu thích - trong thực tế sẽ lấy từ database
$favoriteProducts = [
    [
        'id' => 1,
        'name' => 'Dell XPS 13 Plus',
        'price' => 32990000,
        'old_price' => 35990000,
        'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400&h=250&fit=crop',
        'rating' => 4.8,
        'reviews' => 124,
        'badge' => 'sale',
        'discount' => 8,
        'brand' => 'Dell',
        'category' => 'Ultrabook'
    ],
    [
        'id' => 2,
        'name' => 'MacBook Air M2',
        'price' => 28990000,
        'old_price' => null,
        'image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=400&h=250&fit=crop',
        'rating' => 4.9,
        'reviews' => 89,
        'badge' => 'new',
        'discount' => 0,
        'brand' => 'Apple',
        'category' => 'Ultrabook'
    ],
    [
        'id' => 3,
        'name' => 'HP Pavilion Gaming 15',
        'price' => 18990000,
        'old_price' => 21990000,
        'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=400&h=250&fit=crop',
        'rating' => 4.5,
        'reviews' => 156,
        'badge' => 'sale',
        'discount' => 14,
        'brand' => 'HP',
        'category' => 'Gaming'
    ],
    [
        'id' => 4,
        'name' => 'Lenovo ThinkPad X1 Carbon',
        'price' => 42990000,
        'old_price' => null,
        'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=250&fit=crop',
        'rating' => 4.7,
        'reviews' => 78,
        'badge' => null,
        'discount' => 0,
        'brand' => 'Lenovo',
        'category' => 'Business'
    ],
    [
        'id' => 5,
        'name' => 'ASUS ROG Strix G15',
        'price' => 25990000,
        'old_price' => 27990000,
        'image' => 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?w=400&h=250&fit=crop',
        'rating' => 4.6,
        'reviews' => 203,
        'badge' => 'sale',
        'discount' => 7,
        'brand' => 'ASUS',
        'category' => 'Gaming'
    ],
    [
        'id' => 6,
        'name' => 'Acer Swift 3',
        'price' => 15990000,
        'old_price' => null,
        'image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=400&h=250&fit=crop',
        'rating' => 4.3,
        'reviews' => 92,
        'badge' => 'new',
        'discount' => 0,
        'brand' => 'Acer',
        'category' => 'Ultrabook'
    ]
];

function formatPrice($price) {
    return number_format($price, 0, ',', '.') . '₫';
}

function renderStars($rating) {
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
    
    $stars = '';
    for ($i = 0; $i < $fullStars; $i++) {
        $stars .= '<i class="fas fa-star"></i>';
    }
    if ($halfStar) {
        $stars .= '<i class="fas fa-star-half-alt"></i>';
    }
    for ($i = 0; $i < $emptyStars; $i++) {
        $stars .= '<i class="far fa-star"></i>';
    }
    
    return $stars;
}
?>

<div class="container-fluid py-5">
    <!-- Breadcrumb -->
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item"><a href="?option=home" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sản phẩm yêu thích</li>
            </ol>
        </nav>
    </div>

    <div class="container">
        <!-- Header Section -->
        <div class="text-center mb-5">
            <h1 class="section-title display-4">
                <i class="fas fa-heart text-danger me-3"></i>
                Sản phẩm yêu thích
            </h1>
            <p class="lead text-muted">Danh sách những sản phẩm laptop bạn đã yêu thích</p>
        </div>

        <?php if (empty($favoriteProducts)): ?>
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="empty-state-container">
                    <i class="fas fa-heart-broken text-muted" style="font-size: 4rem; margin-bottom: 20px;"></i>
                    <h3 class="text-muted mb-3">Chưa có sản phẩm yêu thích</h3>
                    <p class="text-muted mb-4">Hãy khám phá và thêm những sản phẩm bạn yêu thích vào danh sách này</p>
                    <a href="?option=sanpham" class="btn btn-custom btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Khám phá sản phẩm
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Filter and Sort Section -->
            <div class="filter-container mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <span class="fw-bold me-3">Lọc theo:</span>
                            <select class="form-select form-select-sm me-3" style="width: auto;" id="brandFilter">
                                <option value="">Tất cả thương hiệu</option>
                                <option value="Dell">Dell</option>
                                <option value="Apple">Apple</option>
                                <option value="HP">HP</option>
                                <option value="Lenovo">Lenovo</option>
                                <option value="ASUS">ASUS</option>
                                <option value="Acer">Acer</option>
                            </select>
                            <select class="form-select form-select-sm" style="width: auto;" id="categoryFilter">
                                <option value="">Tất cả danh mục</option>
                                <option value="Gaming">Gaming</option>
                                <option value="Ultrabook">Ultrabook</option>
                                <option value="Business">Business</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-md-end">
                            <span class="fw-bold me-3">Sắp xếp:</span>
                            <select class="form-select form-select-sm" style="width: auto;" id="sortFilter">
                                <option value="name">Tên A-Z</option>
                                <option value="price-asc">Giá thấp đến cao</option>
                                <option value="price-desc">Giá cao đến thấp</option>
                                <option value="rating">Đánh giá cao nhất</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Count -->
            <div class="mb-4">
                <p class="text-muted">
                    <i class="fas fa-heart text-danger me-2"></i>
                    Hiển thị <strong><?php echo count($favoriteProducts); ?></strong> sản phẩm yêu thích
                </p>
            </div>

            <!-- Products Grid -->
            <div class="row" id="productsGrid">
                <?php foreach ($favoriteProducts as $product): ?>
                    <div class="col-lg-4 col-md-6 mb-4 product-item" 
                         data-brand="<?php echo $product['brand']; ?>" 
                         data-category="<?php echo $product['category']; ?>"
                         data-price="<?php echo $product['price']; ?>"
                         data-rating="<?php echo $product['rating']; ?>"
                         data-name="<?php echo $product['name']; ?>">
                        <div class="product-card position-relative">
                            <!-- Product Badge -->
                            <?php if ($product['badge']): ?>
                                <div class="product-badge <?php echo $product['badge']; ?>">
                                    <?php if ($product['badge'] == 'sale'): ?>
                                        -<?php echo $product['discount']; ?>%
                                    <?php elseif ($product['badge'] == 'new'): ?>
                                        Mới
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Favorite Button -->
                            <button class="favorite-btn active" onclick="toggleFavorite(<?php echo $product['id']; ?>, this)">
                                <i class="fas fa-heart"></i>
                            </button>

                            <!-- Product Image -->
                            <div class="position-relative overflow-hidden">
                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                            </div>

                            <!-- Product Info -->
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-light text-dark"><?php echo $product['brand']; ?></span>
                                    <span class="badge bg-secondary"><?php echo $product['category']; ?></span>
                                </div>
                                
                                <h5 class="mb-2">
                                    <a href="?option=chitietsanpham&id=<?php echo $product['id']; ?>" 
                                       class="text-decoration-none text-dark">
                                        <?php echo $product['name']; ?>
                                    </a>
                                </h5>

                                <!-- Rating -->
                                <div class="rating-stars mb-2">
                                    <?php echo renderStars($product['rating']); ?>
                                    <span class="ms-1 text-muted small">
                                        (<?php echo $product['reviews']; ?> đánh giá)
                                    </span>
                                </div>

                                <!-- Price -->
                                <div class="price-section mb-3">
                                    <span class="price"><?php echo formatPrice($product['price']); ?></span>
                                    <?php if ($product['old_price']): ?>
                                        <span class="old-price"><?php echo formatPrice($product['old_price']); ?></span>
                                    <?php endif; ?>
                                </div>

                                <!-- Actions -->
                                <div class="product-actions">
                                    <button class="btn btn-custom flex-fill me-2" onclick="addToCart(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-shopping-cart me-1"></i>
                                        Thêm vào giỏ
                                    </button>
                                    <a href="?option=chitietsanpham&id=<?php echo $product['id']; ?>" 
                                       class="btn btn-outline-secondary flex-fill">
                                        <i class="fas fa-eye me-1"></i>
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-5">
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <button class="btn btn-outline-danger btn-lg" onclick="clearAllFavorites()">
                        <i class="fas fa-trash-alt me-2"></i>
                        Xóa tất cả yêu thích
                    </button>
                    <button class="btn btn-custom btn-lg" onclick="addAllToCart()">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Thêm tất cả vào giỏ hàng
                    </button>
                    <a href="?option=sanpham" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>
                        Thêm sản phẩm khác
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Filter and Sort Functions
document.getElementById('brandFilter').addEventListener('change', filterProducts);
document.getElementById('categoryFilter').addEventListener('change', filterProducts);
document.getElementById('sortFilter').addEventListener('change', sortProducts);

function filterProducts() {
    const brandFilter = document.getElementById('brandFilter').value;
    const categoryFilter = document.getElementById('categoryFilter').value;
    const products = document.querySelectorAll('.product-item');
    
    products.forEach(product => {
        const brand = product.dataset.brand;
        const category = product.dataset.category;
        
        const matchBrand = !brandFilter || brand === brandFilter;
        const matchCategory = !categoryFilter || category === categoryFilter;
        
        if (matchBrand && matchCategory) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

function sortProducts() {
    const sortBy = document.getElementById('sortFilter').value;
    const grid = document.getElementById('productsGrid');
    const products = Array.from(document.querySelectorAll('.product-item'));
    
    products.sort((a, b) => {
        switch (sortBy) {
            case 'name':
                return a.dataset.name.localeCompare(b.dataset.name);
            case 'price-asc':
                return parseInt(a.dataset.price) - parseInt(b.dataset.price);
            case 'price-desc':
                return parseInt(b.dataset.price) - parseInt(a.dataset.price);
            case 'rating':
                return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
            default:
                return 0;
        }
    });
    
    products.forEach(product => grid.appendChild(product));
}

// Product Actions
function toggleFavorite(productId, button) {
    if (button.classList.contains('active')) {
        // Remove from favorites
        button.classList.remove('active');
        button.closest('.product-item').style.display = 'none';
        
        // Show toast notification
        showToast('Đã xóa khỏi danh sách yêu thích!', 'warning');
        
        // Simulate removal with fade effect
        setTimeout(() => {
            button.closest('.product-item').remove();
            updateFavoriteCount();
        }, 300);
    }
}

function addToCart(productId) {
    // Simulate adding to cart
    showToast('Đã thêm sản phẩm vào giỏ hàng!', 'success');
    
    // Update cart count
    const cartCount = document.getElementById('cartCount');
    const currentCount = parseInt(cartCount.textContent);
    cartCount.textContent = currentCount + 1;
}

function clearAllFavorites() {
    if (confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm yêu thích?')) {
        const products = document.querySelectorAll('.product-item');
        products.forEach(product => {
            product.style.opacity = '0';
            product.style.transform = 'translateY(-20px)';
        });
        
        setTimeout(() => {
            document.getElementById('productsGrid').innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-heart-broken text-muted" style="font-size: 4rem; margin-bottom: 20px;"></i>
                    <h3 class="text-muted mb-3">Đã xóa tất cả sản phẩm yêu thích</h3>
                    <p class="text-muted mb-4">Hãy khám phá và thêm những sản phẩm bạn yêu thích vào danh sách này</p>
                    <a href="?option=sanpham" class="btn btn-custom btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Khám phá sản phẩm
                    </a>
                </div>
            `;
        }, 300);
        
        showToast('Đã xóa tất cả sản phẩm yêu thích!', 'warning');
    }
}

function addAllToCart() {
    const visibleProducts = document.querySelectorAll('.product-item:not([style*="display: none"])');
    const count = visibleProducts.length;
    
    if (count > 0) {
        showToast(`Đã thêm ${count} sản phẩm vào giỏ hàng!`, 'success');
        
        // Update cart count
        const cartCount = document.getElementById('cartCount');
        const currentCount = parseInt(cartCount.textContent);
        cartCount.textContent = currentCount + count;
    }
}

function updateFavoriteCount() {
    const visibleProducts = document.querySelectorAll('.product-item:not([style*="display: none"])').length;
    const countText = document.querySelector('.text-muted strong');
    if (countText) {
        countText.textContent = visibleProducts;
    }
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('successToast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = toast.querySelector('.toast-header i');
    
    // Update message and icon based on type
    toastMessage.textContent = message;
    
    if (type === 'warning') {
        toastIcon.className = 'fas fa-exclamation-triangle text-warning me-2';
        toast.querySelector('.toast-header strong').textContent = 'Thông báo';
    } else {
        toastIcon.className = 'fas fa-check-circle text-success me-2';
        toast.querySelector('.toast-header strong').textContent = 'Thành công';
    }
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations for product cards
    const products = document.querySelectorAll('.product-item');
    products.forEach((product, index) => {
        product.style.opacity = '0';
        product.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            product.style.transition = 'all 0.5s ease';
            product.style.opacity = '1';
            product.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>