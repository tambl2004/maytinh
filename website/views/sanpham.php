<?php
// Mock data cho demo - Trong thực tế sẽ lấy từ database
$products = [
    [
        'id' => 1,
        'name' => 'Dell XPS 13 Plus',
        'brand' => 'Dell',
        'price' => 32000000,
        'old_price' => 35000000,
        'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=300&h=200&fit=crop',
        'cpu' => 'Intel i7',
        'ram' => '16GB',
        'storage' => 'SSD 512GB',
        'screen' => '13"',
        'category' => 'văn phòng',
        'badge' => 'sale',
        'rating' => 4.8,
        'is_new' => false
    ],
    [
        'id' => 2,
        'name' => 'HP Pavilion Gaming 15',
        'brand' => 'HP',
        'price' => 18000000,
        'old_price' => null,
        'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=300&h=200&fit=crop',
        'cpu' => 'Intel i5',
        'ram' => '8GB',
        'storage' => 'SSD 256GB',
        'screen' => '15"',
        'category' => 'gaming',
        'badge' => 'new',
        'rating' => 4.5,
        'is_new' => true
    ],
    [
        'id' => 3,
        'name' => 'MacBook Air M2',
        'brand' => 'Apple',
        'price' => 28000000,
        'old_price' => null,
        'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300&h=200&fit=crop',
        'cpu' => 'Apple M2',
        'ram' => '8GB',
        'storage' => 'SSD 256GB',
        'screen' => '13"',
        'category' => 'văn phòng',
        'badge' => 'hot',
        'rating' => 4.9,
        'is_new' => true
    ],
    [
        'id' => 4,
        'name' => 'ASUS ROG Strix G15',
        'brand' => 'Asus',
        'price' => 25000000,
        'old_price' => 27000000,
        'image' => 'https://images.unsplash.com/photo-1593640408182-31174422f5b9?w=300&h=200&fit=crop',
        'cpu' => 'AMD Ryzen 7',
        'ram' => '16GB',
        'storage' => 'SSD 512GB',
        'screen' => '15"',
        'category' => 'gaming',
        'badge' => 'sale',
        'rating' => 4.7,
        'is_new' => false
    ],
    [
        'id' => 5,
        'name' => 'Lenovo ThinkPad X1 Carbon',
        'brand' => 'Lenovo',
        'price' => 35000000,
        'old_price' => null,
        'image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=300&h=200&fit=crop',
        'cpu' => 'Intel i7',
        'ram' => '16GB',
        'storage' => 'SSD 1TB',
        'screen' => '14"',
        'category' => 'văn phòng',
        'badge' => null,
        'rating' => 4.6,
        'is_new' => false
    ],
    [
        'id' => 6,
        'name' => 'HP Spectre x360',
        'brand' => 'HP',
        'price' => 30000000,
        'old_price' => null,
        'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300&h=200&fit=crop',
        'cpu' => 'Intel i7',
        'ram' => '16GB',
        'storage' => 'SSD 512GB',
        'screen' => '13"',
        'category' => 'đồ họa',
        'badge' => null,
        'rating' => 4.5,
        'is_new' => false
    ],
    [
        'id' => 7,
        'name' => 'Dell Inspiron 15 3000',
        'brand' => 'Dell',
        'price' => 12000000,
        'old_price' => null,
        'image' => 'https://images.unsplash.com/photo-1484788984921-03950022c9ef?w=300&h=200&fit=crop',
        'cpu' => 'Intel i3',
        'ram' => '8GB',
        'storage' => 'HDD 1TB',
        'screen' => '15"',
        'category' => 'học tập',
        'badge' => null,
        'rating' => 4.2,
        'is_new' => false
    ],
    [
        'id' => 8,
        'name' => 'ASUS ZenBook 14',
        'brand' => 'Asus',
        'price' => 22000000,
        'old_price' => 24000000,
        'image' => 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=300&h=200&fit=crop',
        'cpu' => 'AMD Ryzen 5',
        'ram' => '8GB',
        'storage' => 'SSD 512GB',
        'screen' => '14"',
        'category' => 'văn phòng',
        'badge' => 'sale',
        'rating' => 4.4,
        'is_new' => false
    ]
];

// Lấy tham số từ URL
$selected_brand = $_GET['brand'] ?? '';
$selected_category = $_GET['category'] ?? '';
$selected_cpu = $_GET['cpu'] ?? '';
$selected_ram = $_GET['ram'] ?? '';
$selected_storage = $_GET['storage'] ?? '';
$selected_screen = $_GET['screen'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$sort_by = $_GET['sort'] ?? 'default';
$page = max(1, (int)($_GET['page'] ?? 1));
$products_per_page = 8;

// Áp dụng bộ lọc
$filtered_products = array_filter($products, function($product) use (
    $selected_brand, $selected_category, $selected_cpu, $selected_ram, 
    $selected_storage, $selected_screen, $price_min, $price_max
) {
    if ($selected_brand && $product['brand'] !== $selected_brand) return false;
    if ($selected_category && $product['category'] !== $selected_category) return false;
    if ($selected_cpu && strpos($product['cpu'], $selected_cpu) === false) return false;
    if ($selected_ram && strpos($product['ram'], $selected_ram) === false) return false;
    if ($selected_storage && strpos($product['storage'], $selected_storage) === false) return false;
    if ($selected_screen && strpos($product['screen'], $selected_screen) === false) return false;
    if ($price_min && $product['price'] < $price_min * 1000000) return false;
    if ($price_max && $product['price'] > $price_max * 1000000) return false;
    return true;
});

// Sắp xếp
switch ($sort_by) {
    case 'price_asc':
        usort($filtered_products, fn($a, $b) => $a['price'] - $b['price']);
        break;
    case 'price_desc':
        usort($filtered_products, fn($a, $b) => $b['price'] - $a['price']);
        break;
    case 'newest':
        usort($filtered_products, fn($a, $b) => $b['is_new'] - $a['is_new']);
        break;
    case 'rating':
        usort($filtered_products, fn($a, $b) => $b['rating'] <=> $a['rating']);
        break;
}

// Phân trang
$total_products = count($filtered_products);
$total_pages = ceil($total_products / $products_per_page);
$offset = ($page - 1) * $products_per_page;
$current_products = array_slice($filtered_products, $offset, $products_per_page);
?>

<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="?option=home">Trang chủ</a></li>
            <li class="breadcrumb-item active">Sản phẩm</li>
            <?php if ($selected_category): ?>
                <li class="breadcrumb-item active"><?= ucfirst($selected_category) ?></li>
            <?php endif; ?>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="section-title">
                <?php if ($selected_category): ?>
                    Laptop <?= ucfirst($selected_category) ?>
                <?php elseif ($selected_brand): ?>
                    Laptop <?= $selected_brand ?>
                <?php else: ?>
                    Tất cả sản phẩm
                <?php endif; ?>
            </h2>
            <p class="text-muted">Tìm thấy <?= $total_products ?> sản phẩm</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="d-flex justify-content-end align-items-center gap-3">
                <label class="form-label mb-0">Sắp xếp:</label>
                <select class="form-select" style="width: auto;" onchange="updateSort(this.value)">
                    <option value="default" <?= $sort_by === 'default' ? 'selected' : '' ?>>Mặc định</option>
                    <option value="price_asc" <?= $sort_by === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                    <option value="price_desc" <?= $sort_by === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                    <option value="newest" <?= $sort_by === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="rating" <?= $sort_by === 'rating' ? 'selected' : '' ?>>Đánh giá cao</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="filter-container">
                <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Bộ lọc</h5>
                
                <form id="filterForm" method="GET">
                    <input type="hidden" name="option" value="sanpham">
                    
                    <!-- Price Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">Khoảng giá</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm" 
                                       name="price_min" placeholder="Từ (triệu)" 
                                       value="<?= htmlspecialchars($price_min) ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm" 
                                       name="price_max" placeholder="Đến (triệu)"
                                       value="<?= htmlspecialchars($price_max) ?>">
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block">Giá phổ biến:</small>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setPriceRange(10, 20)">10-20tr</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setPriceRange(20, 30)">20-30tr</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setPriceRange(30, 40)">30-40tr+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">Thương hiệu</h6>
                        <select class="form-select form-select-sm" name="brand">
                            <option value="">Tất cả thương hiệu</option>
                            <option value="Dell" <?= $selected_brand === 'Dell' ? 'selected' : '' ?>>Dell</option>
                            <option value="HP" <?= $selected_brand === 'HP' ? 'selected' : '' ?>>HP</option>
                            <option value="Asus" <?= $selected_brand === 'Asus' ? 'selected' : '' ?>>Asus</option>
                            <option value="Lenovo" <?= $selected_brand === 'Lenovo' ? 'selected' : '' ?>>Lenovo</option>
                            <option value="Apple" <?= $selected_brand === 'Apple' ? 'selected' : '' ?>>Apple</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">Mục đích sử dụng</h6>
                        <select class="form-select form-select-sm" name="category">
                            <option value="">Tất cả mục đích</option>
                            <option value="gaming" <?= $selected_category === 'gaming' ? 'selected' : '' ?>>Gaming</option>
                            <option value="văn phòng" <?= $selected_category === 'văn phòng' ? 'selected' : '' ?>>Văn phòng</option>
                            <option value="đồ họa" <?= $selected_category === 'đồ họa' ? 'selected' : '' ?>>Đồ họa</option>
                            <option value="học tập" <?= $selected_category === 'học tập' ? 'selected' : '' ?>>Học tập</option>
                        </select>
                    </div>

                    <!-- CPU Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">Bộ xử lý</h6>
                        <select class="form-select form-select-sm" name="cpu">
                            <option value="">Tất cả CPU</option>
                            <option value="Intel i3" <?= $selected_cpu === 'Intel i3' ? 'selected' : '' ?>>Intel i3</option>
                            <option value="Intel i5" <?= $selected_cpu === 'Intel i5' ? 'selected' : '' ?>>Intel i5</option>
                            <option value="Intel i7" <?= $selected_cpu === 'Intel i7' ? 'selected' : '' ?>>Intel i7</option>
                            <option value="AMD Ryzen 5" <?= $selected_cpu === 'AMD Ryzen 5' ? 'selected' : '' ?>>AMD Ryzen 5</option>
                            <option value="AMD Ryzen 7" <?= $selected_cpu === 'AMD Ryzen 7' ? 'selected' : '' ?>>AMD Ryzen 7</option>
                            <option value="Apple M2" <?= $selected_cpu === 'Apple M2' ? 'selected' : '' ?>>Apple M2</option>
                        </select>
                    </div>

                    <!-- RAM Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">RAM</h6>
                        <select class="form-select form-select-sm" name="ram">
                            <option value="">Tất cả RAM</option>
                            <option value="8GB" <?= $selected_ram === '8GB' ? 'selected' : '' ?>>8GB</option>
                            <option value="16GB" <?= $selected_ram === '16GB' ? 'selected' : '' ?>>16GB</option>
                            <option value="32GB" <?= $selected_ram === '32GB' ? 'selected' : '' ?>>32GB</option>
                        </select>
                    </div>

                    <!-- Storage Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">Ổ cứng</h6>
                        <select class="form-select form-select-sm" name="storage">
                            <option value="">Tất cả ổ cứng</option>
                            <option value="SSD" <?= strpos($selected_storage, 'SSD') !== false ? 'selected' : '' ?>>SSD</option>
                            <option value="HDD" <?= strpos($selected_storage, 'HDD') !== false ? 'selected' : '' ?>>HDD</option>
                        </select>
                    </div>

                    <!-- Screen Size Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">Kích thước màn hình</h6>
                        <select class="form-select form-select-sm" name="screen">
                            <option value="">Tất cả kích thước</option>
                            <option value="13" <?= $selected_screen === '13' ? 'selected' : '' ?>>13 inch</option>
                            <option value="14" <?= $selected_screen === '14' ? 'selected' : '' ?>>14 inch</option>
                            <option value="15" <?= $selected_screen === '15' ? 'selected' : '' ?>>15 inch</option>
                            <option value="17" <?= $selected_screen === '17' ? 'selected' : '' ?>>17 inch</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-custom">
                            <i class="fas fa-search me-2"></i>Áp dụng bộ lọc
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="fas fa-times me-2"></i>Xóa bộ lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="row" id="productsContainer">
                <?php if (empty($current_products)): ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Không tìm thấy sản phẩm nào</h4>
                        <p class="text-muted">Hãy thử điều chỉnh bộ lọc để tìm thấy sản phẩm phù hợp</p>
                        <button class="btn btn-custom" onclick="clearFilters()">Xóa tất cả bộ lọc</button>
                    </div>
                <?php else: ?>
                    <?php foreach ($current_products as $product): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="product-card position-relative">
                                <?php if ($product['badge']): ?>
                                    <div class="product-badge <?= $product['badge'] ?>">
                                        <?php
                                        echo match($product['badge']) {
                                            'new' => 'Mới',
                                            'sale' => 'Giảm giá',
                                            'hot' => 'Hot',
                                            default => 'Mới'
                                        };
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <button class="favorite-btn" onclick="toggleFavorite(<?= $product['id'] ?>)">
                                    <i class="far fa-heart"></i>
                                </button>
                                
                                <div class="position-relative overflow-hidden">
                                    <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                                </div>
                                
                                <div class="p-3">
                                    <h6 class="mb-2 text-truncate"><?= htmlspecialchars($product['name']) ?></h6>
                                    
                                    <div class="rating-stars mb-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= floor($product['rating']) ? 'text-warning' : 'text-muted' ?>"></i>
                                        <?php endfor; ?>
                                        <small class="text-muted ms-1">(<?= $product['rating'] ?>)</small>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-microchip me-1"></i><?= $product['cpu'] ?> | 
                                            <i class="fas fa-memory me-1"></i><?= $product['ram'] ?> | 
                                            <i class="fas fa-hdd me-1"></i><?= $product['storage'] ?>
                                        </small>
                                    </div>
                                    
                                    <div class="price mb-3">
                                        <?php if ($product['old_price']): ?>
                                            <span class="old-price"><?= number_format($product['old_price']) ?>đ</span>
                                        <?php endif; ?>
                                        <span class="price"><?= number_format($product['price']) ?>đ</span>
                                    </div>
                                    
                                    <div class="product-actions d-flex gap-2">
                                        <button class="btn btn-custom flex-fill" onclick="addToCart(<?= $product['id'] ?>)">
                                            <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                                        </button>
                                        <a href="?option=chitietsanpham&id=<?= $product['id'] ?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Phân trang sản phẩm">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= buildPaginationUrl($page - 1) ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);
                        
                        if ($start_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= buildPaginationUrl(1) ?>">1</a>
                            </li>
                            <?php if ($start_page > 2): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="<?= buildPaginationUrl($i) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($end_page < $total_pages): ?>
                            <?php if ($end_page < $total_pages - 1): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= buildPaginationUrl($total_pages) ?>"><?= $total_pages ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= buildPaginationUrl($page + 1) ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
function buildPaginationUrl($page) {
    $params = $_GET;
    $params['page'] = $page;
    return '?' . http_build_query($params);
}
?>

<script>
// Cập nhật sắp xếp
function updateSort(value) {
    const url = new URL(window.location);
    if (value === 'default') {
        url.searchParams.delete('sort');
    } else {
        url.searchParams.set('sort', value);
    }
    url.searchParams.delete('page'); // Reset về trang 1
    window.location.href = url.toString();
}

// Đặt khoảng giá nhanh
function setPriceRange(min, max) {
    document.querySelector('input[name="price_min"]').value = min;
    document.querySelector('input[name="price_max"]').value = max;
}

// Xóa tất cả bộ lọc
function clearFilters() {
    window.location.href = '?option=sanpham';
}

// Toggle yêu thích
function toggleFavorite(productId) {
    const btn = event.target.closest('.favorite-btn');
    const icon = btn.querySelector('i');
    
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        btn.classList.add('active');
        showToast('Đã thêm vào danh sách yêu thích!');
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        btn.classList.remove('active');
        showToast('Đã xóa khỏi danh sách yêu thích!');
    }
}

// Thêm vào giỏ hàng
function addToCart(productId) {
    // Lấy số lượng hiện tại từ badge
    let currentCount = parseInt(document.getElementById('cartCount').textContent) || 0;
    
    // Cập nhật số lượng
    currentCount++;
    document.getElementById('cartCount').textContent = currentCount;
    
    // Hiển thị toast
    showToast('Sản phẩm đã được thêm vào giỏ hàng!');
    
    // Lưu vào localStorage (tùy chọn)
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({ id: productId, quantity: 1 });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Hiển thị toast notification
function showToast(message) {
    document.getElementById('toastMessage').textContent = message;
    const toast = new bootstrap.Toast(document.getElementById('successToast'));
    toast.show();
}

// Load số lượng giỏ hàng khi trang được tải
document.addEventListener('DOMContentLoaded', function() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('cartCount').textContent = totalItems;
});

// Auto-submit form khi thay đổi select
document.querySelectorAll('#filterForm select').forEach(select => {
    select.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
</script>