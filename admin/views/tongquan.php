<!-- Mobile Menu Toggle -->
<div class="d-md-none p-3">
    <button class="btn btn-outline-secondary" type="button" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>
</div>

<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1">Dashboard</h2>
            <p class="text-muted mb-0">Tổng quan quản lý cửa hàng laptop</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-custom">
                <i class="bi bi-download"></i> Xuất báo cáo
            </button>
            <button class="btn btn-primary btn-custom">
                <i class="bi bi-plus-lg"></i> Thêm sản phẩm
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary-gradient me-3">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Doanh thu hôm nay</div>
                        <div class="h4 mb-0 font-weight-bold">₫45,200,000</div>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> 12.5% từ hôm qua
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success-gradient me-3">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Đơn hàng mới</div>
                        <div class="h4 mb-0 font-weight-bold">24</div>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> 8.2% từ tuần trước
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning-gradient me-3">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Sản phẩm bán chạy</div>
                        <div class="h4 mb-0 font-weight-bold">156</div>
                        <small class="text-warning">
                            <i class="bi bi-arrow-right"></i> Ổn định
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-danger-gradient me-3">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Sản phẩm hết hàng</div>
                        <div class="h4 mb-0 font-weight-bold">8</div>
                        <small class="text-danger">
                            <i class="bi bi-arrow-up"></i> Cần bổ sung
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-xl-8 mb-4">
        <div class="chart-container">
            <h5 class="mb-3">Doanh thu 7 ngày qua</h5>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <div class="col-xl-4 mb-4">
        <div class="chart-container">
            <h5 class="mb-3">Sản phẩm bán chạy</h5>
            <canvas id="productChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Đơn hàng gần đây</h5>
        <a href="#" class="btn btn-outline-primary btn-sm">Xem tất cả</a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>#ORD001</strong></td>
                    <td>Nguyễn Văn A</td>
                    <td>MacBook Pro M3</td>
                    <td>1</td>
                    <td>₫42,900,000</td>
                    <td><span class="status-badge bg-success text-white">Đã giao</span></td>
                    <td>25/05/2025</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">Chi tiết</button>
                    </td>
                </tr>
                <tr>
                    <td><strong>#ORD002</strong></td>
                    <td>Trần Thị B</td>
                    <td>Dell XPS 13</td>
                    <td>2</td>
                    <td>₫56,800,000</td>
                    <td><span class="status-badge bg-warning text-dark">Đang xử lý</span></td>
                    <td>24/05/2025</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">Chi tiết</button>
                    </td>
                </tr>
                <tr>
                    <td><strong>#ORD003</strong></td>
                    <td>Lê Minh C</td>
                    <td>HP Pavilion 15</td>
                    <td>1</td>
                    <td>₫18,500,000</td>
                    <td><span class="status-badge bg-info text-white">Đang giao</span></td>
                    <td>24/05/2025</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">Chi tiết</button>
                    </td>
                </tr>
                <tr>
                    <td><strong>#ORD004</strong></td>
                    <td>Phạm Văn D</td>
                    <td>ASUS ROG Strix</td>
                    <td>1</td>
                    <td>₫35,200,000</td>
                    <td><span class="status-badge bg-danger text-white">Đã hủy</span></td>
                    <td>23/05/2025</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">Chi tiết</button>
                    </td>
                </tr>
                <tr>
                    <td><strong>#ORD005</strong></td>
                    <td>Hoàng Thị E</td>
                    <td>Lenovo ThinkPad</td>
                    <td>3</td>
                    <td>₫72,600,000</td>
                    <td><span class="status-badge bg-success text-white">Đã giao</span></td>
                    <td>23/05/2025</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">Chi tiết</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>