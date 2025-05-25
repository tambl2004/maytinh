<?php
$userId = $_SESSION['id'];

// Lấy danh sách sản phẩm từ database
$query = "SELECT s.*, t.ten as thuonghieu, d.ten as danhmuc, 
          JSON_UNQUOTE(JSON_EXTRACT(s.thongso, '$.cpu')) as cpu,
          JSON_UNQUOTE(JSON_EXTRACT(s.thongso, '$.ram')) as ram,
          JSON_UNQUOTE(JSON_EXTRACT(s.thongso, '$.storage')) as storage,
          JSON_UNQUOTE(JSON_EXTRACT(s.thongso, '$.screen')) as screen,
          AVG(dg.diemso) as rating
          FROM sanpham s 
          LEFT JOIN thuonghieu t ON s.thuonghieu_id = t.id 
          LEFT JOIN danhmuc d ON s.danhmuc_id = d.id
          LEFT JOIN danhgia dg ON s.id = dg.sanpham_id
          WHERE s.trangthai = 'hoatdong'
          GROUP BY s.id";
$result = mysqli_query($conn, $query);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = [
        'id' => $row['id'],
        'name' => $row['ten'],
        'brand' => $row['thuonghieu'],
        'price' => $row['gia'],
        'old_price' => $row['giacu'],
        'image' => $row['hinhanh'],
        'cpu' => $row['cpu'],
        'ram' => $row['ram'],
        'storage' => $row['storage'],
        'screen' => $row['screen'],
        'category' => $row['danhmuc'],
        'badge' => $row['noibat'] ? 'hot' : ($row['giacu'] ? 'sale' : 'new'),
        'rating' => $row['rating'] ? round($row['rating'], 1) : 0,
        'is_new' => (strtotime($row['ngaytao']) > strtotime('-30 days'))
    ];
}

// Lấy danh sách yêu thích của người dùng
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

// Lấy danh sách CPU, RAM, storage, screen từ thông số sản phẩm
$cpus = array_unique(array_column($products, 'cpu'));
$rams = array_unique(array_column($products, 'ram'));
$storages = array_unique(array_column($products, 'storage'));
$screens = array_unique(array_column($products, 'screen'));

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
    if ($selected_cpu && $product['cpu'] !== $selected_cpu) return false;
    if ($selected_ram && $product['ram'] !== $selected_ram) return false;
    if ($selected_storage && strpos($product['storage'], $selected_storage) === false) return false;
    if ($selected_screen && $product['screen'] !== $selected_screen) return false;
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
        usort($filtered_products, fn($a, $b) => $b['price'] - $b['price']);
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
            <li class="breadcrumb-item"><a href="?option=home" class="text-decoration-none">Trang chủ</a></li>
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
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= htmlspecialchars($brand) ?>" <?= $selected_brand === $brand ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($brand) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">Mục đích sử dụng</h6>
                        <select class="form-select form-select-sm" name="category">
                            <option value="">Tất cả mục đích</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category) ?>" <?= $selected_category === $category ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- CPU Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">Bộ xử lý</h6>
                        <select class="form-select form-select-sm" name="cpu">
                            <option value="">Tất cả CPU</option>
                            <?php foreach ($cpus as $cpu): ?>
                                <option value="<?= htmlspecialchars($cpu) ?>" <?= $selected_cpu === $cpu ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cpu) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- RAM Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">RAM</h6>
                        <select class="form-select form-select-sm" name="ram">
                            <option value="">Tất cả RAM</option>
                            <?php foreach ($rams as $ram): ?>
                                <option value="<?= htmlspecialchars($ram) ?>" <?= $selected_ram === $ram ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ram) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Storage Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">Ổ cứng</h6>
                        <select class="form-select form-select-sm" name="storage">
                            <option value="">Tất cả ổ cứng</option>
                            <?php foreach ($storages as $storage): ?>
                                <option value="<?= htmlspecialchars($storage) ?>" <?= $selected_storage === $storage ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($storage) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Screen Size Filter -->
                    <div class="mb-4">
                        <h6 class="filter-title">Kích thước màn hình</h6>
                        <select class="form-select form-select-sm" name="screen">
                            <option value="">Tất cả kích thước</option>
                            <?php foreach ($screens as $screen): ?>
                                <option value="<?= htmlspecialchars($screen) ?>" <?= $selected_screen === $screen ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($screen) ?>
                                </option>
                            <?php endforeach; ?>
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
                                
                                <button class="favorite-btn <?= in_array($product['id'], $favoriteIds) ? 'active' : '' ?>" 
                                        onclick="toggleFavorite(<?= $product['id'] ?>)"
                                        data-id="<?= $product['id'] ?>">
                                    <i class="<?= in_array($product['id'], $favoriteIds) ? 'fas' : 'far' ?> fa-heart"></i>
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
                                        <small class="text-muted ms-1">(<?= $product['rating'] ?: 'Chưa có' ?>)</small>
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
// Dữ liệu sản phẩm từ PHP
const products = <?php echo json_encode($current_products); ?>;

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
    document.getElementById('filterForm').submit();
}

// Xóa tất cả bộ lọc
function clearFilters() {
    window.location.href = '?option=sanpham';
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

// Toggle yêu thích
function toggleFavorite(productId) {
    const btn = event.target.closest('.favorite-btn');
    const icon = btn.querySelector('i');
    
    fetch('controllers/favorite_controller.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=toggle&product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
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

// Thêm vào giỏ hàng
function addToCart(productId) {
    fetch('controllers/cart_controller.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&product_id=${productId}&quantity=1`
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
            const product = products.find(p => p.id === productId);
            if (product) {
                showToast(`Đã thêm "${product.name}" vào giỏ hàng!`, 'success');
            } else {
                showToast('Đã thêm sản phẩm vào giỏ hàng!', 'success');
            }
        } else {
            showToast(data.message || 'Không thể thêm vào giỏ hàng!', 'warning');
        }
    })
    .catch(error => {
        console.error('Lỗi chi tiết:', error);
        showToast('Đã xảy ra lỗi: ' + error.message, 'warning');
    });
}

// Load số lượng giỏ hàng khi trang được tải
document.addEventListener('DOMContentLoaded', function() {
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

    // Hiệu ứng hiển thị sản phẩm
    document.querySelectorAll('.product-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Auto-submit form khi thay đổi select
    document.querySelectorAll('#filterForm select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
});
</script>