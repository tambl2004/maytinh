<?php
require_once '../config/connect.php';

// Xử lý thêm mã giảm giá
if (isset($_POST['add_discount'])) {
    $code = trim($_POST['code']);
    $discount_type = $_POST['discount_type'];
    $discount_value = floatval($_POST['discount_value']);
    $min_order_value = floatval($_POST['min_order_value']);
    $max_discount_value = !empty($_POST['max_discount_value']) ? floatval($_POST['max_discount_value']) : null;
    $start_date = str_replace('T', ' ', $_POST['start_date']); // Chuyển đổi định dạng datetime-local
    $end_date = str_replace('T', ' ', $_POST['end_date']);     // Chuyển đổi định dạng datetime-local
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $usage_limit = !empty($_POST['usage_limit']) ? intval($_POST['usage_limit']) : null;

    // Validate đầu vào
    $errors = [];
    if (empty($code)) {
        $errors[] = "Mã giảm giá không được để trống.";
    }
    if ($discount_value <= 0) {
        $errors[] = "Giá trị giảm giá phải lớn hơn 0.";
    }
    if (strtotime($start_date) >= strtotime($end_date)) {
        $errors[] = "Ngày bắt đầu phải nhỏ hơn ngày kết thúc.";
    }

    // Kiểm tra mã giảm giá đã tồn tại
    $check_query = "SELECT id FROM magiamgia WHERE code = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $code);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        $errors[] = "Mã giảm giá đã tồn tại.";
    }
    $check_stmt->close();

    if (empty($errors)) {
        $query = "INSERT INTO magiamgia (code, discount_type, discount_value, min_order_value, max_discount_value, start_date, end_date, is_active, usage_limit, used_count) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssddssssi", $code, $discount_type, $discount_value, $min_order_value, $max_discount_value, $start_date, $end_date, $is_active, $usage_limit);
        
        if ($stmt->execute()) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Thêm mã giảm giá thành công.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => { window.location.href = '?option=magiamgia'; });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể thêm mã giảm giá. Vui lòng thử lại.',
                });
            </script>";
        }
        $stmt->close();
    } else {
        $error_message = implode("<br>", $errors);
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                html: '$error_message',
            });
        </script>";
    }
}

// Xử lý sửa mã giảm giá
if (isset($_POST['edit_discount'])) {
    $id = intval($_POST['id']);
    $code = trim($_POST['code']);
    $discount_type = $_POST['discount_type'];
    $discount_value = floatval($_POST['discount_value']);
    $min_order_value = floatval($_POST['min_order_value']);
    $max_discount_value = !empty($_POST['max_discount_value']) ? floatval($_POST['max_discount_value']) : null;
    $start_date = str_replace('T', ' ', $_POST['start_date']); // Chuyển đổi định dạng
    $end_date = str_replace('T', ' ', $_POST['end_date']);     // Chuyển đổi định dạng
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $usage_limit = !empty($_POST['usage_limit']) ? intval($_POST['usage_limit']) : null;

    // Validate đầu vào
    $errors = [];
    if (empty($code)) {
        $errors[] = "Mã giảm giá không được để trống.";
    }
    if ($discount_value <= 0) {
        $errors[] = "Giá trị giảm giá phải lớn hơn 0.";
    }
    if (strtotime($start_date) >= strtotime($end_date)) {
        $errors[] = "Ngày bắt đầu phải nhỏ hơn ngày kết thúc.";
    }

    // Kiểm tra mã giảm giá đã tồn tại (trừ mã hiện tại)
    $check_query = "SELECT id FROM magiamgia WHERE code = ? AND id != ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("si", $code, $id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        $errors[] = "Mã giảm giá đã tồn tại.";
    }
    $check_stmt->close();

    if (empty($errors)) {
        $query = "UPDATE magiamgia SET code = ?, discount_type = ?, discount_value = ?, min_order_value = ?, max_discount_value = ?, start_date = ?, end_date = ?, is_active = ?, usage_limit = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        // Sửa chuỗi định dạng để bao gồm id
        $stmt->bind_param("ssddssssii", $code, $discount_type, $discount_value, $min_order_value, $max_discount_value, $start_date, $end_date, $is_active, $usage_limit, $id);
        
        if ($stmt->execute()) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Cập nhật mã giảm giá thành công.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => { window.location.href = '?option=magiamgia'; });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể cập nhật mã giảm giá. Vui lòng thử lại.',
                });
            </script>";
        }
        $stmt->close();
    } else {
        $error_message = implode("<br>", $errors);
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                html: '$error_message',
            });
        </script>";
    }
}

// Xử lý xóa mã giảm giá
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM magiamgia WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: 'Xóa mã giảm giá thành công.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => { window.location.href = '?option=magiamgia'; });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Không thể xóa mã giảm giá. Vui lòng thử lại.',
            });
        </script>";
    }
    $stmt->close();
}

// Lấy danh sách mã giảm giá
$query = "SELECT * FROM magiamgia";
$result = $conn->query($query);
?>

    <div class="page-header d-flex justify-content-between align-items-center">
        <h3 class="fw-bold">Quản Lý Mã Giảm Giá</h3>
        <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#addDiscountModal">
            <i class="bi bi-plus-circle"></i> Thêm mã giảm giá
        </button>
    </div>

    <!-- Nút thêm mã giảm giá -->

    <!-- Bảng danh sách mã giảm giá -->
    <div class="table-container">
        <table class="table product-table">
            <thead>
                <tr>
                    <th scope="col">Mã</th>
                    <th scope="col">Loại giảm</th>
                    <th scope="col">Giá trị</th>
                    <th scope="col">Đơn tối thiểu</th>
                    <th scope="col">Giảm tối đa</th>
                    <th scope="col">Ngày bắt đầu</th>
                    <th scope="col">Ngày kết thúc</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Giới hạn sử dụng</th>
                    <th scope="col">Đã dùng</th>
                    <th scope="col">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="product-row">
                        <td><?php echo htmlspecialchars($row['code']); ?></td>
                        <td><?php echo $row['discount_type'] == 'percentage' ? 'Phần trăm' : 'VNĐ'; ?></td>
                        <td><?php echo $row['discount_type'] == 'percentage' ? $row['discount_value'] . '%' : number_format($row['discount_value'], 0, ',', '.') . ' VNĐ'; ?></td>
                        <td><?php echo number_format($row['min_order_value'], 0, ',', '.') . ' VNĐ'; ?></td>
                        <td><?php echo $row['max_discount_value'] ? number_format($row['max_discount_value'], 0, ',', '.') . ' VNĐ' : '-'; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['start_date'])); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['end_date'])); ?></td>
                        <td>
                            <span class="status-badge <?php echo $row['is_active'] ? 'bg-success-gradient' : 'bg-danger-gradient'; ?>">
                                <?php echo $row['is_active'] ? 'Hoạt động' : 'Không hoạt động'; ?>
                            </span>
                        </td>
                        <td><?php echo $row['usage_limit'] ? $row['usage_limit'] : 'Không giới hạn'; ?></td>
                        <td><?php echo $row['used_count']; ?></td>
                        <td>
                            <a href="#" class="btn btn-primary btn-action-custom btn-action edit-discount" 
                               data-bs-toggle="modal" 
                               data-bs-target="#editDiscountModal"
                               data-id="<?php echo $row['id']; ?>"
                               data-code="<?php echo htmlspecialchars($row['code']); ?>"
                               data-discount_type="<?php echo $row['discount_type']; ?>"
                               data-discount_value="<?php echo $row['discount_value']; ?>"
                               data-min_order_value="<?php echo $row['min_order_value']; ?>"
                               data-max_discount_value="<?php echo $row['max_discount_value']; ?>"
                               data-start_date="<?php echo $row['start_date']; ?>"
                               data-end_date="<?php echo $row['end_date']; ?>"
                               data-is_active="<?php echo $row['is_active']; ?>"
                               data-usage_limit="<?php echo $row['usage_limit']; ?>">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <a href="?option=magiamgia&action=delete&id=<?php echo $row['id']; ?>" 
                               class="btn btn-danger btn-action-custom btn-action delete-discount"
                               onclick="return confirmDelete()">
                                <i class="bi bi-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal thêm mã giảm giá -->
    <div class="modal fade" id="addDiscountModal" tabindex="-1" aria-labelledby="addDiscountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary-gradient">
                    <h5 class="modal-title text-white" id="addDiscountModalLabel">Thêm Mã Giảm Giá</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDiscountForm" method="POST" action="">
                        <div class="mb-3">
                            <label for="code" class="form-label">Mã giảm giá</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="discount_type" class="form-label">Loại giảm giá</label>
                            <select class="form-select" id="discount_type" name="discount_type" required>
                                <option value="percentage">Phần trăm</option>
                                <option value="fixed">VNĐ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="discount_value" class="form-label">Giá trị giảm</label>
                            <input type="number" step="0.01" class="form-control" id="discount_value" name="discount_value" required>
                        </div>
                        <div class="mb-3">
                            <label for="min_order_value" class="form-label">Đơn hàng tối thiểu</label>
                            <input type="number" step="0.01" class="form-control" id="min_order_value" name="min_order_value" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="max_discount_value" class="form-label">Giảm giá tối đa</label>
                            <input type="number" step="0.01" class="form-control" id="max_discount_value" name="max_discount_value">
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Ngày bắt đầu</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Ngày kết thúc</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Giới hạn sử dụng</label>
                            <input type="number" class="form-control" id="usage_limit" name="usage_limit" placeholder="Để trống nếu không giới hạn">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">Hoạt động</label>
                        </div>
                        <button type="submit" name="add_discount" class="btn btn-primary btn-custom">Thêm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal sửa mã giảm giá -->
    <div class="modal fade" id="editDiscountModal" tabindex="-1" aria-labelledby="editDiscountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary-gradient">
                    <h5 class="modal-title text-white" id="editDiscountModalLabel">Sửa Mã Giảm Giá</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDiscountForm" method="POST" action="">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_code" class="form-label">Mã giảm giá</label>
                            <input type="text" class="form-control" id="edit_code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_discount_type" class="form-label">Loại giảm giá</label>
                            <select class="form-select" id="edit_discount_type" name="discount_type" required>
                                <option value="percentage">Phần trăm</option>
                                <option value="fixed">VNĐ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_discount_value" class="form-label">Giá trị giảm</label>
                            <input type="number" step="0.01" class="form-control" id="edit_discount_value" name="discount_value" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_min_order_value" class="form-label">Đơn hàng tối thiểu</label>
                            <input type="number" step="0.01" class="form-control" id="edit_min_order_value" name="min_order_value" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="edit_max_discount_value" class="form-label">Giảm giá tối đa</label>
                            <input type="number" step="0.01" class="form-control" id="edit_max_discount_value" name="max_discount_value">
                        </div>
                        <div class="mb-3">
                            <label for="edit_start_date" class="form-label">Ngày bắt đầu</label>
                            <input type="datetime-local" class="form-control" id="edit_start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_end_date" class="form-label">Ngày kết thúc</label>
                            <input type="datetime-local" class="form-control" id="edit_end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_usage_limit" class="form-label">Giới hạn sử dụng</label>
                            <input type="number" class="form-control" id="edit_usage_limit" name="usage_limit" placeholder="Để trống nếu không giới hạn">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active">
                            <label class="form-check-label" for="edit_is_active">Hoạt động</label>
                        </div>
                        <button type="submit" name="edit_discount" class="btn btn-primary btn-custom">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


<script>
// Xác nhận xóa mã giảm giá
function confirmDelete() {
    return confirm('Bạn có chắc muốn xóa mã giảm giá này?');
}

// Điền dữ liệu vào modal sửa
document.querySelectorAll('.edit-discount').forEach(button => {
    button.addEventListener('click', function() {
        const modal = document.querySelector('#editDiscountModal');
        modal.querySelector('#edit_id').value = this.dataset.id;
        modal.querySelector('#edit_code').value = this.dataset.code;
        modal.querySelector('#edit_discount_type').value = this.dataset.discount_type;
        modal.querySelector('#edit_discount_value').value = this.dataset.discount_value;
        modal.querySelector('#edit_min_order_value').value = this.dataset.min_order_value;
        modal.querySelector('#edit_max_discount_value').value = this.dataset.max_discount_value || '';
        // Chuyển đổi định dạng ngày tháng, bỏ giây nếu có
        const startDate = this.dataset.start_date.split(':').slice(0, 2).join(':').replace(' ', 'T');
        const endDate = this.dataset.end_date.split(':').slice(0, 2).join(':').replace(' ', 'T');
        modal.querySelector('#edit_start_date').value = startDate;
        modal.querySelector('#edit_end_date').value = endDate;
        modal.querySelector('#edit_usage_limit').value = this.dataset.usage_limit || '';
        modal.querySelector('#edit_is_active').checked = this.dataset.is_active == '1';
    });
});
</script>

