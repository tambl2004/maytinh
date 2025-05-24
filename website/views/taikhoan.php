
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar điều hướng -->
            <div class="col-lg-3 mb-4">
                <div class="account-sidebar filter-container">
                    <h3 class="section-title">Tài khoản của bạn</h3>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#personal-info" data-bs-toggle="tab">Thông tin cá nhân</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#change-password" data-bs-toggle="tab">Đổi mật khẩu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#order-history" data-bs-toggle="tab">Lịch sử đơn hàng</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Nội dung chính -->
            <div class="col-lg-9">
                <div class="tab-content tab-content-custom">
                    <!-- Thông tin cá nhân -->
                    <div class="tab-pane fade show active" id="personal-info">
                        <div class="contact-form-card">
                            <h3 class="section-title">Thông tin cá nhân</h3>
                            <form>
                                <div class="mb-3">
                                    <label for="fullName" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control form-control-custom" id="fullName" value="Nguyễn Văn A" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control form-control-custom" id="email" value="nguyenvana@example.com" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control form-control-custom" id="phone" value="0123 456 789" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <textarea class="form-control form-control-custom" id="address" rows="3" required>123 Đường Công Nghệ, TP.HCM</textarea>
                                </div>
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-submit-form">Lưu thay đổi</button>
                                    <button type="reset" class="btn btn-reset-form">Hủy</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Đổi mật khẩu -->
                    <div class="tab-pane fade" id="change-password">
                        <div class="contact-form-card">
                            <h3 class="section-title">Đổi mật khẩu</h3>
                            <form>
                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label">Mật khẩu hiện tại</label>
                                    <input type="password" class="form-control form-control-custom" id="currentPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control form-control-custom" id="newPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" class="form-control form-control-custom" id="confirmPassword" required>
                                </div>
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-submit-form">Lưu thay đổi</button>
                                    <button type="reset" class="btn btn-reset-form">Hủy</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Lịch sử đơn hàng -->
                    <div class="tab-pane fade" id="order-history">
                        <div class="cart-container">
                            <h3 class="section-title">Lịch sử đơn hàng</h3>
                            <div class="table-responsive">
                                <table class="table specs-table">
                                    <thead>
                                        <tr>
                                            <th>Mã đơn hàng</th>
                                            <th>Ngày đặt</th>
                                            <th>Trạng thái</th>
                                            <th>Tổng tiền</th>
                                            <th>Chi tiết</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#DH12345</td>
                                            <td>20/05/2025</td>
                                            <td><span class="badge bg-success">Đã giao</span></td>
                                            <td>15,000,000 VNĐ</td>
                                            <td><button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#orderDetailModal">Xem chi tiết</button></td>
                                        </tr>
                                        <tr>
                                            <td>#DH12346</td>
                                            <td>15/05/2025</td>
                                            <td><span class="badge bg-warning">Đang xử lý</span></td>
                                            <td>8,500,000 VNĐ</td>
                                            <td><button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#orderDetailModal">Xem chi tiết</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal chi tiết đơn hàng -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">Chi tiết đơn hàng #DH12345</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="cart-container">
                        <h6 class="mb-3">Thông tin đơn hàng</h6>
                        <p><strong>Ngày đặt:</strong> 20/05/2025</p>
                        <p><strong>Trạng thái:</strong> <span class="badge bg-success">Đã giao</span></p>
                        <p><strong>Tổng tiền:</strong> 15,000,000 VNĐ</p>
                        <hr>
                        <h6>Sản phẩm</h6>
                        <div class="cart-item">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="cart-item-image">
                                        <img src="https://via.placeholder.com/120" alt="Product">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="cart-item-title">Laptop Dell XPS 13</h6>
                                    <p class="cart-item-specs">Core i7, 16GB RAM, 512GB SSD</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="cart-item-specs">Số lượng: 1</p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <p class="current-price-cart">15,000,000 VNĐ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
