<!-- File: tongquan.php -->

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
            <h2 class="mb-1">Tổng Quan</h2>
            <p class="text-muted mb-0">Tổng quan quản lý cửa hàng laptop</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-custom" onclick="exportReport()">
                <i class="bi bi-download"></i> Xuất báo cáo
            </button>
            <a href="?option=sanpham&action=add" class="btn btn-primary btn-custom">
                <i class="bi bi-plus-lg"></i> Thêm sản phẩm
            </a>
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
                        <div class="h4 mb-0 font-weight-bold" id="todayRevenue">₫0</div>
                        <small id="revenueChange" class="text-success">
                            <i class="bi bi-arrow-up"></i> 0% từ hôm qua
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
                        <div class="h4 mb-0 font-weight-bold" id="newOrders">0</div>
                        <small id="orderChange" class="text-success">
                            <i class="bi bi-arrow-up"></i> 0% từ tuần trước
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
                        <div class="h4 mb-0 font-weight-bold" id="topProducts">0</div>
                        <small id="productStatus" class="text-warning">
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
                        <div class="h4 mb-0 font-weight-bold" id="outOfStock">0</div>
                        <small class="text-danger">
                            <i class="bi bi-arrow-up"></i> Cần bổ sung
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card table-container h-100">
            <div class="card-header">Doanh thu tuần qua</div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card table-container h-100">
            <div class="card-header">Phân bố sản phẩm theo thương hiệu</div>
            <div class="card-body">
                <canvas id="productChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<script>
    // Hàm định dạng tiền tệ
    function formatCurrency(value) {
        return '₫' + value.toLocaleString('vi-VN');
    }

    // Hàm tải dữ liệu tổng quan
    function loadDashboardData() {
        fetch('api/dashboard.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('todayRevenue').textContent = formatCurrency(data.todayRevenue);
                document.getElementById('newOrders').textContent = data.newOrders;
                document.getElementById('topProducts').textContent = data.topProducts;
                document.getElementById('outOfStock').textContent = data.outOfStock;
                
                document.getElementById('revenueChange').innerHTML = 
                    `<i class="bi bi-arrow-${data.revenueChange > 0 ? 'up' : 'down'}"></i> ${Math.abs(data.revenueChange)}% từ hôm qua`;
                document.getElementById('orderChange').innerHTML = 
                    `<i class="bi bi-arrow-${data.orderChange > 0 ? 'up' : 'down'}"></i> ${Math.abs(data.orderChange)}% từ tuần trước`;
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể tải dữ liệu tổng quan!'
                });
            });
    }


    // Hàm xuất báo cáo
    function exportReport() {
        Swal.fire({
            title: 'Xuất báo cáo',
            text: 'Bạn muốn xuất báo cáo dưới dạng PDF hay Excel?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'PDF',
            cancelButtonText: 'Excel'
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = 'api/export_report.php?format=pdf';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = 'api/export_report.php?format=excel';
            }
        });
    }

    // Tải dữ liệu khi trang được load
    document.addEventListener('DOMContentLoaded', () => {
        loadDashboardData();
        loadRecentOrders();
    });
</script>