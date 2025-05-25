<?php
require_once '../config/connect.php';

// Lấy danh sách danh mục và thương hiệu
$danhmuc_query = "SELECT * FROM danhmuc";
$danhmuc_result = mysqli_query($conn, $danhmuc_query);
$danhmuc_list = mysqli_fetch_all($danhmuc_result, MYSQLI_ASSOC);

$thuonghieu_query = "SELECT * FROM thuonghieu";
$thuonghieu_result = mysqli_query($conn, $thuonghieu_query);
$thuonghieu_list = mysqli_fetch_all($thuonghieu_result, MYSQLI_ASSOC);

// Xử lý tìm kiếm và lọc
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$danhmuc_filter = isset($_GET['danhmuc']) ? (int)$_GET['danhmuc'] : 0;
$thuonghieu_filter = isset($_GET['thuonghieu']) ? (int)$_GET['thuonghieu'] : 0;
$trangthai_filter = isset($_GET['trangthai']) ? $_GET['trangthai'] : '';
$noibat_filter = isset($_GET['noibat']) ? (int)$_GET['noibat'] : -1;

// Phân trang
$per_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

// Xây dựng query
$query = "SELECT s.*, d.ten AS danhmuc_ten, t.ten AS thuonghieu_ten 
          FROM sanpham s 
          LEFT JOIN danhmuc d ON s.danhmuc_id = d.id 
          LEFT JOIN thuonghieu t ON s.thuonghieu_id = t.id 
          WHERE 1=1";

if ($search) {
    $query .= " AND (s.ten LIKE '%$search%' OR s.id = '$search')";
}
if ($danhmuc_filter) {
    $query .= " AND s.danhmuc_id = $danhmuc_filter";
}
if ($thuonghieu_filter) {
    $query .= " AND s.thuonghieu_id = $thuonghieu_filter";
}
if ($trangthai_filter) {
    $query .= " AND s.trangthai = '$trangthai_filter'";
}
if ($noibat_filter != -1) {
    $query .= " AND s.noibat = $noibat_filter";
}

$count_query = "SELECT COUNT(*) as total FROM sanpham s WHERE 1=1";
if ($search) {
    $count_query .= " AND (s.ten LIKE '%$search%' OR s.id = '$search')";
}
if ($danhmuc_filter) {
    $count_query .= " AND s.danhmuc_id = $danhmuc_filter";
}
if ($thuonghieu_filter) {
    $count_query .= " AND s.thuonghieu_id = $thuonghieu_filter";
}
if ($trangthai_filter) {
    $count_query .= " AND s.trangthai = '$trangthai_filter'";
}
if ($noibat_filter != -1) {
    $count_query .= " AND s.noibat = $noibat_filter";
}

$count_result = mysqli_query($conn, $count_query);
$total_rows = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_rows / $per_page);

$query .= " LIMIT $offset, $per_page";
$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Xử lý thêm/sửa sản phẩm
$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action === 'add' || $action === 'edit') {
            $ten = mysqli_real_escape_string($conn, $_POST['ten']);
            $mota = mysqli_real_escape_string($conn, $_POST['mota']);
            $gia = (float)$_POST['gia'];
            $giacu = !empty($_POST['giacu']) ? (float)$_POST['giacu'] : null;
            $danhmuc_id = (int)$_POST['danhmuc_id'];
            $thuonghieu_id = (int)$_POST['thuonghieu_id'];
            $cpu = mysqli_real_escape_string($conn, $_POST['cpu']);
            $ram = mysqli_real_escape_string($conn, $_POST['ram']);
            $storage = mysqli_real_escape_string($conn, $_POST['storage']);
            $screen = mysqli_real_escape_string($conn, $_POST['screen']);
            $thongso = json_encode([
                'cpu' => $cpu,
                'ram' => $ram,
                'storage' => $storage,
                'screen' => $screen
            ]);
            $soluongton = (int)$_POST['soluongton'];
            $noibat = isset($_POST['noibat']) ? 1 : 0;
            $trangthai = $_POST['trangthai'];
            
            // Validate
            if ($gia <= 0) $errors[] = "Giá phải lớn hơn 0";
            if (empty($cpu) || empty($ram) || empty($storage) || empty($screen)) {
                $errors[] = "Vui lòng nhập đầy đủ thông số kỹ thuật";
            }
            
            // Xử lý hình ảnh
            $hinhanh = '';
            if (!empty($_FILES['hinhanh']['name'])) {
                $target_dir = "../uploads/";
                $hinhanh = $target_dir . basename($_FILES['hinhanh']['name']);
                move_uploaded_file($_FILES['hinhanh']['tmp_name'], $hinhanh);
            } else {
                if ($action === 'add') {
                    $errors[] = "Hình ảnh là bắt buộc khi thêm sản phẩm";
                }
            }
            
            if (empty($errors)) {
                if ($action === 'add') {
                    $query = "INSERT INTO sanpham (ten, mota, gia, giacu, hinhanh, danhmuc_id, thuonghieu_id, thongso, soluongton, noibat, trangthai)
                              VALUES ('$ten', '$mota', $gia, " . ($giacu ? $giacu : 'NULL') . ", '$hinhanh', $danhmuc_id, $thuonghieu_id, '$thongso', $soluongton, $noibat, '$trangthai')";
                    $success = "Thêm sản phẩm thành công!";
                } else {
                    $id = (int)$_POST['id'];
                    $query = "UPDATE sanpham SET 
                              ten='$ten', 
                              mota='$mota', 
                              gia=$gia, 
                              giacu=" . ($giacu ? $giacu : 'NULL') . ", 
                              " . ($hinhanh ? "hinhanh='$hinhanh'," : "") . "
                              danhmuc_id=$danhmuc_id, 
                              thuonghieu_id=$thuonghieu_id, 
                              thongso='$thongso', 
                              soluongton=$soluongton, 
                              noibat=$noibat, 
                              trangthai='$trangthai' 
                              WHERE id=$id";
                    $success = "Cập nhật sản phẩm thành công!";
                }
                mysqli_query($conn, $query) or die(mysqli_error($conn));
            }
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            $query = "UPDATE sanpham SET trangthai='khonghoatdong' WHERE id=$id";
            if (mysqli_query($conn, $query)) {
                $success = "Xóa sản phẩm thành công!";
            } else {
                $errors[] = "Xóa sản phẩm thất bại!";
            }
        }
    }
}
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="h2">Quản Lý Sản Phẩm</h1>
    <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="bi bi-plus-circle"></i> Thêm Sản Phẩm
    </button>
</div>

<!-- Form tìm kiếm và lọc -->
<div class="table-container mb-4 p-4">
    <form method="GET" class="row g-3">
        <input type="hidden" name="option" value="sanpham">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên hoặc mã" value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="col">
            <select name="danhmuc" class="form-select">
                <option value="0">Tất cả danh mục</option>
                <?php foreach ($danhmuc_list as $dm): ?>
                    <option value="<?php echo $dm['id']; ?>" <?php echo $danhmuc_filter == $dm['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($dm['ten']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col">
            <select name="thuonghieu" class="form-select">
                <option value="0">Tất cả thương hiệu</option>
                <?php foreach ($thuonghieu_list as $th): ?>
                    <option value="<?php echo $th['id']; ?>" <?php echo $thuonghieu_filter == $th['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($th['ten']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col">
            <select name="trangthai" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="hoatdong" <?php echo $trangthai_filter == 'hoatdong' ? 'selected' : ''; ?>>Hoạt động</option>
                <option value="khonghoatdong" <?php echo $trangthai_filter == 'khonghoatdong' ? 'selected' : ''; ?>>Không hoạt động</option>
            </select>
        </div>
       
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-custom w-100"><i class="bi bi-funnel"></i> Lọc</button>
        </div>
    </form>
</div>

<!-- Danh sách sản phẩm -->
<div class="table-container p-4">
    <table class="table table-hover table-bordered product-table">
        <thead class="table-light">
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Hình ảnh</th>
                <th>Tên</th>
                <th>Danh mục</th>
                <th>Thương hiệu</th>
                <th>Giá</th>
                <th>Tồn kho</th>
                <th>Trạng thái</th>
                <th class="text-center">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr class="product-row">
                    <td class="text-center align-middle"><?php echo $product['id']; ?></td>
                    <td class="text-center align-middle"><img src="<?php echo htmlspecialchars($product['hinhanh']); ?>" alt="Hình ảnh" class="product-image"></td>
                    <td class="align-middle"><?php echo htmlspecialchars($product['ten']); ?></td>
                    <td class="align-middle"><?php echo htmlspecialchars($product['danhmuc_ten']); ?></td>
                    <td class="align-middle"><?php echo htmlspecialchars($product['thuonghieu_ten']); ?></td>
                    <td class="align-middle text-primary font-weight-bold"><?php echo number_format($product['gia'], 0, ',', '.'); ?> ₫</td>
                    <td class="text-center align-middle"><?php echo $product['soluongton']; ?></td>
                    
                    <td class="text-center align-middle">
                        <span class="status-badge <?php echo $product['trangthai'] == 'hoatdong' ? 'bg-success-gradient' : 'bg-danger-gradient'; ?>">
                            <?php echo $product['trangthai'] == 'hoatdong' ? 'Hoạt động' : 'Không hoạt động'; ?>
                        </span>
                    </td>
                    <td class="text-center align-middle">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-primary btn-action-custom" data-bs-toggle="modal" data-bs-target="#editProductModal" 
                                    onclick='loadProduct(<?php echo json_encode($product); ?>)'>
                                <i class="bi bi-pencil"></i> Sửa
                            </button>
                            <button class="btn btn-sm btn-danger btn-action-custom" data-bs-toggle="modal" data-bs-target="#deleteProductModal" 
                                    onclick="setDeleteId(<?php echo $product['id']; ?>)">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Phân trang -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mt-4">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                    <a class="page-link" href="?option=sanpham&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&danhmuc=<?php echo $danhmuc_filter; ?>&thuonghieu=<?php echo $thuonghieu_filter; ?>&trangthai=<?php echo $trangthai_filter; ?>&noibat=<?php echo $noibat_filter; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Modal Thêm Sản Phẩm -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-gradient text-white">
                <h5 class="modal-title" id="addProductModalLabel">Thêm Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tên sản phẩm</label>
                            <input type="text" name="ten" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hình ảnh</label>
                            <input type="file" name="hinhanh" id="add_hinhanh" class="form-control" accept="image/*" required onchange="previewImage(this, 'add_image_preview')">
                            <div class="mt-2">
                                <img id="add_image_preview" src="#" alt="Xem trước hình ảnh" class="img-preview d-none">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="mota" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá</label>
                            <input type="number" name="gia" class="form-control" required min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá cũ</label>
                            <input type="number" name="giacu" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Danh mục</label>
                            <select name="danhmuc_id" class="form-select" required>
                                <?php foreach ($danhmuc_list as $dm): ?>
                                    <option value="<?php echo $dm['id']; ?>"><?php echo htmlspecialchars($dm['ten']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Thương hiệu</label>
                            <select name="thuonghieu_id" class="form-select" required>
                                <?php foreach ($thuonghieu_list as $th): ?>
                                    <option value="<?php echo $th['id']; ?>"><?php echo htmlspecialchars($th['ten']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Thông số kỹ thuật</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" name="cpu" class="form-control" placeholder="CPU" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="ram" class="form-control" placeholder="RAM" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="storage" class="form-control" placeholder="Bộ nhớ" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="screen" class="form-control" placeholder="Màn hình" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số lượng tồn</label>
                            <input type="number" name="soluongton" class="form-control" required min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trangthai" class="form-select" required>
                                <option value="hoatdong">Hoạt động</option>
                                <option value="khonghoatdong">Không hoạt động</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" name="noibat" class="form-check-input" id="add_noibat">
                                <label class="form-check-label" for="add_noibat">Nổi bật</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-custom" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary btn-custom">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Sản Phẩm -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-gradient text-white">
                <h5 class="modal-title" id="editProductModalLabel">Sửa Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tên sản phẩm</label>
                            <input type="text" name="ten" id="edit_ten" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Hình ảnh</label>
                            <input type="file" name="hinhanh" id="edit_hinhanh" class="form-control" accept="image/*" onchange="previewImage(this, 'edit_image_preview')">
                            <div class="mt-2">
                                <img id="edit_image_preview" src="#" alt="Xem trước hình ảnh" class="img-preview">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="mota" id="edit_mota" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá</label>
                            <input type="number" name="gia" id="edit_gia" class="form-control" required min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá cũ</label>
                            <input type="number" name="giacu" id="edit_giacu" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Danh mục</label>
                            <select name="danhmuc_id" id="edit_danhmuc_id" class="form-select" required>
                                <?php foreach ($danhmuc_list as $dm): ?>
                                    <option value="<?php echo $dm['id']; ?>"><?php echo htmlspecialchars($dm['ten']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Thương hiệu</label>
                            <select name="thuonghieu_id" id="edit_thuonghieu_id" class="form-select" required>
                                <?php foreach ($thuonghieu_list as $th): ?>
                                    <option value="<?php echo $th['id']; ?>"><?php echo htmlspecialchars($th['ten']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Thông số kỹ thuật</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" name="cpu" id="edit_cpu" class="form-control" placeholder="CPU" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="ram" id="edit_ram" class="form-control" placeholder="RAM" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="storage" id="edit_storage" class="form-control" placeholder="Bộ nhớ" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="screen" id="edit_screen" class="form-control" placeholder="Màn hình" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số lượng tồn</label>
                            <input type="number" name="soluongton" id="edit_soluongton" class="form-control" required min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trangthai" id="edit_trangthai" class="form-select" required>
                                <option value="hoatdong">Hoạt động</option>
                                <option value="khonghoatdong">Không hoạt động</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" name="noibat" id="edit_noibat" class="form-check-input">
                                <label class="form-check-label" for="edit_noibat">Nổi bật</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-custom" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary btn-custom">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa Sản Phẩm -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger-gradient text-white">
                <h5 class="modal-title" id="deleteProductModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="delete_id">
                    <p>Bạn có chắc muốn xóa sản phẩm này? (Sẽ chuyển trạng thái thành không hoạt động)</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-custom" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger btn-custom">Xóa</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.classList.add('d-none');
        }
    }

    function loadProduct(product) {
        document.getElementById('edit_id').value = product.id;
        document.getElementById('edit_ten').value = product.ten;
        document.getElementById('edit_mota').value = product.mota;
        document.getElementById('edit_gia').value = product.gia;
        document.getElementById('edit_giacu').value = product.giacu || '';
        document.getElementById('edit_danhmuc_id').value = product.danhmuc_id;
        document.getElementById('edit_thuonghieu_id').value = product.thuonghieu_id;
        const thongso = JSON.parse(product.thongso);
        document.getElementById('edit_cpu').value = thongso.cpu;
        document.getElementById('edit_ram').value = thongso.ram;
        document.getElementById('edit_storage').value = thongso.storage;
        document.getElementById('edit_screen').value = thongso.screen;
        document.getElementById('edit_soluongton').value = product.soluongton;
        document.getElementById('edit_trangthai').value = product.trangthai;
        document.getElementById('edit_noibat').checked = product.noibat == 1;
        const preview = document.getElementById('edit_image_preview');
        preview.src = product.hinhanh;
        preview.classList.remove('d-none');
    }

    function setDeleteId(id) {
        document.getElementById('delete_id').value = id;
    }

    // Xử lý thông báo sau khi submit
    <?php if (!empty($success)): ?>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '<?php echo addslashes($success); ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
           window.location.href = '?option=sanpham';
        });
    <?php elseif (!empty($errors)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            html: '<?php echo addslashes(implode('<br>', $errors)); ?>',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
</script>