<?php

$product_id = (int)$_GET['id'];
$userId = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

// Lấy thông tin sản phẩm từ database
$query = "SELECT s.*, t.ten as thuonghieu, d.ten as danhmuc, 
          JSON_UNQUOTE(JSON_EXTRACT(s.thongso, '$.cpu')) as cpu,
          JSON_UNQUOTE(JSON_EXTRACT(s.thongso, '$.ram')) as ram,
          JSON_UNQUOTE(JSON_EXTRACT(s.thongso, '$.storage')) as storage,
          JSON_UNQUOTE(JSON_EXTRACT(s.thongso, '$.screen')) as screen,
          AVG(dg.diemso) as rating,
          COUNT(dg.id) as review_count
          FROM sanpham s 
          LEFT JOIN thuonghieu t ON s.thuonghieu_id = t.id 
          LEFT JOIN danhmuc d ON s.danhmuc_id = d.id
          LEFT JOIN danhgia dg ON s.id = dg.sanpham_id
          WHERE s.id = ? AND s.trangthai = 'hoatdong'
          GROUP BY s.id";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

// Kiểm tra xem sản phẩm có tồn tại không
if (!$product) {
    header('Location: ?option=home');
    exit();
}

// Lấy danh sách hình ảnh sản phẩm
$images_query = "SELECT url_hinhanh, hinhanhchinh FROM hinhanhsanpham WHERE sanpham_id = ? ORDER BY hinhanhchinh DESC";
$stmt_images = $conn->prepare($images_query);
$stmt_images->bind_param("i", $product_id);
$stmt_images->execute();
$images_result = $stmt_images->get_result();
$images = [];
while ($row = $images_result->fetch_assoc()) {
    $images[] = $row;
}
$stmt_images->close();

// Nếu không có hình ảnh phụ, sử dụng hình ảnh chính từ bảng sanpham
if (empty($images)) {
    $images[] = ['url_hinhanh' => $product['hinhanh'], 'hinhanhchinh' => 1];
}

// Lấy danh sách đánh giá
$reviews_query = "SELECT dg.*, nd.hoten FROM danhgia dg 
                 LEFT JOIN nguoidung nd ON dg.nguoidung_id = nd.id 
                 WHERE dg.sanpham_id = ? 
                 ORDER BY dg.ngaytao DESC 
                 LIMIT 3";
$stmt_reviews = $conn->prepare($reviews_query);
$stmt_reviews->bind_param("i", $product_id);
$stmt_reviews->execute();
$reviews_result = $stmt_reviews->get_result();
$reviews = [];
while ($row = $reviews_result->fetch_assoc()) {
    $reviews[] = $row;
}
$stmt_reviews->close();

// Tính phần trăm giảm giá
$discount_percent = $product['giacu'] ? round((($product['giacu'] - $product['gia']) / $product['giacu']) * 100) : 0;

// Lấy danh sách yêu thích của người dùng
$is_favorite = false;
if ($userId) {
    $favorite_query = "SELECT id FROM yeuthich WHERE nguoidung_id = ? AND sanpham_id = ?";
    $stmt_favorite = $conn->prepare($favorite_query);
    $stmt_favorite->bind_param("ii", $userId, $product_id);
    $stmt_favorite->execute();
    $is_favorite = $stmt_favorite->get_result()->num_rows > 0;
    $stmt_favorite->close();
}
?>

<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav class="breadcrumb-custom">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?option=home" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="?option=sanpham" class="text-decoration-none">Laptop</a></li>
            <li class="breadcrumb-item"><a href="?option=sanpham&brand=<?= urlencode($product['thuonghieu']) ?>" class="text-decoration-none"><?= htmlspecialchars($product['thuonghieu']) ?></a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($product['ten']) ?></li>
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
                    <?php if ($product['giacu']): ?>
                        <div class="product-badge-large sale">GIẢM GIÁ</div>
                    <?php elseif ($product['noibat']): ?>
                        <div class="product-badge-large hot">HOT</div>
                    <?php else: ?>
                        <div class="product-badge-large new">MỚI</div>
                    <?php endif; ?>
                    
                    <img src="<?= htmlspecialchars($images[0]['url_hinhanh']) ?>" 
                         alt="<?= htmlspecialchars($product['ten']) ?>" 
                         class="main-image" 
                         id="mainImage">
                    
                    <div class="thumbnail-container">
                        <?php foreach ($images as $index => $image): ?>
                            <img src="<?= htmlspecialchars($image['url_hinhanh']) ?>" 
                                 alt="<?= htmlspecialchars($product['ten']) ?>" 
                                 class="thumbnail <?= $image['hinhanhchinh'] ? 'active' : '' ?>" 
                                 onclick="changeImage(this.src)">
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <div class="rating-stars mb-3">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= floor($product['rating'] ?: 0) ? 'text-warning' : 'text-muted' ?>"></i>
                        <?php endfor; ?>
                        <span class="ms-2 text-muted">(<?= $product['review_count'] ?> đánh giá)</span>
                    </div>

                    <h1 class="h2 mb-3"><?= htmlspecialchars($product['ten']) ?></h1>
                    <p class="text-muted mb-4"><?= htmlspecialchars($product['mota']) ?></p>

                    <div class="price-section">
                        <div class="d-flex align-items-center flex-wrap">
                            <span class="current-price"><?= number_format($product['gia']) ?>₫</span>
                            <?php if ($product['giacu']): ?>
                                <span class="original-price"><?= number_format($product['giacu']) ?>₫</span>
                                <span class="discount-percent">-<?= $discount_percent ?>%</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted">Đã bao gồm VAT</small>
                    </div>

                    <!-- Product Options -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Cấu hình:</label>
                        <div class="btn-group d-block" role="group">
                            <input type="radio" class="btn-check" name="config" id="config1" checked>
                            <label class="btn btn-outline-primary me-2 mb-2" for="config1">
                                <?= htmlspecialchars($product['cpu']) ?> | <?= htmlspecialchars($product['ram']) ?> | <?= htmlspecialchars($product['storage']) ?>
                            </label>
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
                                <input type="number" class="quantity-input" id="quantity" value="1" min="1" max="<?= $product['soluongton'] ?>">
                                <button class="quantity-btn" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>
                        <div class="col-auto">
                            <span class="text-success"><i class="fas fa-check-circle"></i> Còn <?= $product['soluongton'] ?> sản phẩm</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex action-buttons mb-4">
                        <button class="btn btn-add-cart" onclick="addToCart(<?= $product['id'] ?>)">
                            <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ hàng
                        </button>
                        <button class="btn btn-buy-now" onclick="buyNow(<?= $product['id'] ?>)">
                            <i class="fas fa-bolt me-2"></i>Mua ngay
                        </button>
                        <button class="favorite-btn-large <?= $is_favorite ? 'active' : '' ?>" 
                                onclick="toggleFavorite(<?= $product['id'] ?>)" 
                                id="favoriteBtn">
                            <i class="<?= $is_favorite ? 'fas' : 'far' ?> fa-heart"></i>
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
            <button class="nav-link1 active" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button">Thông số kỹ thuật</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link1" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">Mô tả chi tiết</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link1" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">Đánh giá (<?= $product['review_count'] ?>)</button>
        </li>
    </ul>
    
    <div class="tab-content tab-content-custom" id="productTabContent">
        <div class="tab-pane fade show active" id="specs" role="tabpanel">
            <div class="specs-table">
                <table class="table mb-0">
                    <tr>
                        <th width="30%">Bộ vi xử lý</th>
                        <td><?= htmlspecialchars($product['cpu']) ?></td>
                    </tr>
                    <tr>
                        <th>RAM</th>
                        <td><?= htmlspecialchars($product['ram']) ?></td>
                    </tr>
                    <tr>
                        <th>Ổ cứng</th>
                        <td><?= htmlspecialchars($product['storage']) ?></td>
                    </tr>
                    <tr>
                        <th>Màn hình</th>
                        <td><?= htmlspecialchars($product['screen']) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="tab-pane fade" id="description" role="tabpanel">
            <h4><?= htmlspecialchars($product['ten']) ?></h4>
            <p><?= htmlspecialchars($product['mota']) ?></p>
        </div>
        
        <div class="tab-pane fade" id="reviews" role="tabpanel">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="display-4 fw-bold text-warning"><?= number_format($product['rating'] ?: 0, 1) ?></div>
                        <div class="rating-stars mb-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= floor($product['rating'] ?: 0) ? 'text-warning' : 'text-muted' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-muted"><?= $product['review_count'] ?> đánh giá</p>
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- Thống kê đánh giá -->
                    <?php
                    $rating_counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
                    $total_reviews = $product['review_count'];
                    foreach ($reviews as $review) {
                        $rating_counts[$review['diemso']]++;
                    }
                    ?>
                    <div class="mb-3">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <div class="d-flex align-items-center mb-2">
                                <span class="me-2"><?= $i ?> sao</span>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" 
                                         style="width: <?= $total_reviews ? ($rating_counts[$i] / $total_reviews * 100) : 0 ?>%"></div>
                                </div>
                                <span class="text-muted"><?= $rating_counts[$i] ?></span>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <!-- Danh sách đánh giá -->
            <?php if (empty($reviews)): ?>
                <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="mb-4">
                        <div class="d-flex mb-2">
                            <strong><?= htmlspecialchars($review['hoten'] ?: 'Khách hàng') ?></strong>
                            <div class="rating-stars ms-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $review['diemso'] ? 'text-warning' : 'text-muted' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-muted ms-auto"><?= date('d/m/Y', strtotime($review['ngaytao'])) ?></span>
                        </div>
                        <p><?= htmlspecialchars($review['binhluan']) ?></p>
                    </div>
                <?php endforeach; ?>
                <?php if ($product['review_count'] > 3): ?>
                    <button class="btn btn-outline-primary" onclick="loadMoreReviews()">Xem thêm đánh giá</button>
                <?php endif; ?>
            <?php endif;
            ?>
        </div>
    </div>
</div>

<script>
    // Product data
    const product = {
        id: <?= $product['id'] ?>,
        name: "<?= addslashes($product['ten']) ?>",
        price: <?= $product['gia'] ?>,
        originalPrice: <?= $product['giacu'] ?: 'null' ?>,
        image: "<?= addslashes($images[0]['url_hinhanh']) ?>"
    };

    function changeImage(src) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
        event.target.classList.add('active');
    }

    function increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue < <?= $product['soluongton'] ?>) {
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

        fetch('controllers/cart_controller.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=add&product_id=${productId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cartCount').textContent = data.count;
                Swal.fire({
                    icon: 'success',
                    title: `Đã thêm "${product.name}" vào giỏ hàng!`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Không thể thêm vào giỏ hàng!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    }

    function buyNow(productId) {
        addToCart(productId);
        setTimeout(() => {
            window.location.href = '?option=thanhtoan';
        }, 500);
    }

    function toggleFavorite(productId) {
        fetch('controllers/favorite_controller.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=toggle&product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const favoriteBtn = document.getElementById('favoriteBtn');
                const icon = favoriteBtn.querySelector('i');
                if (data.action === 'added') {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    favoriteBtn.classList.add('active');
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã thêm vào danh sách yêu thích!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    favoriteBtn.classList.remove('active');
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã xóa khỏi danh sách yêu thích!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Có lỗi xảy ra!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    }

    function loadMoreReviews() {
        // Chức năng này có thể được mở rộng để tải thêm đánh giá qua AJAX
        window.location.href = `?option=chitietsanpham&id=${product.id}&show_all_reviews=true`;
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        fetch('controllers/cart_controller.php?action=get_count')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cartCount').textContent = data.count;
                }
            });
    });
</script>