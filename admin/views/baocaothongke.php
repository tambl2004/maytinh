<!-- File: baocaothongke.php -->

<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1">Báo Cáo Thống Kê</h2>
            <p class="text-muted mb-0">Phân tích chi tiết hoạt động kinh doanh</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-custom" onclick="exportDetailedReport()">
                <i class="bi bi-download"></i> Xuất báo cáo
            </button>
        </div>
    </div>
</div>

<!-- Filter Form -->
<div class="card table-container mb-4">
    <div class="card-header">Lọc Báo Cáo</div>
    <div class="card-body">
        <form id="reportFilterForm" class="row g-3">
            <div class="col-md-3">
                <label for="dateRange" class="form-label">Khoảng thời gian</label>
                <select id="dateRange" class="form-select">
                    <option value="7days">7 ngày qua</option>
                    <option value="30days">30 ngày qua</option>
                    <option value="custom">Tùy chỉnh</option>
                </select>
            </div>
            <div class="col-md-3 d-none" id="customDateRange">
                <label for="startDate" class="form-label">Từ ngày</label>
                <input type="date" id="startDate" class="form-control">
            </div>
            <div class="col-md-3 d-none" id="customDateRangeEnd">
                <label for="endDate" class="form-label">Đến ngày</label>
                <input type="date" id="endDate" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="category" class="form-label">Danh mục</label>
                <select id="category" class="form-select">
                    <option value="">Tất cả</option>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM danhmuc");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['id']}'>{$row['ten']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary btn-custom w-100">Lọc</button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Stats -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary-gradient me-3">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Tổng doanh thu</div>
                        <div class="h4 mb-0 font-weight-bold" id="totalRevenue">₫0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success-gradient me-3">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Tổng đơn hàng</div>
                        <div class="h4 mb-0 font-weight-bold" id="totalOrders">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card stats-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning-gradient me-3">
                        <i class="bi bi-star"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Đánh giá trung bình</div>
                        <div class="h4 mb-0 font-weight-bold" id="avgRating">0.0</div>
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
            <div class="card-header">Doanh thu theo thời gian</div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card table-container h-100">
            <div class="card-header">Top sản phẩm bán chạy</div>
            <div class="card-body">
                <canvas id="productChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Report Table -->
<div class="card table-container">
    <div class="card-header">Chi tiết doanh thu</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table order-table table-hover">
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Doanh thu</th>
                        <th>Số đơn hàng</th>
                        <th>Sản phẩm bán ra</th>
                        <th>Đánh giá trung bình</th>
                    </tr>
                </thead>
                <tbody id="reportTable">
                    <!-- Dữ liệu sẽ được load bằng JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Hàm định dạng tiền tệ
    function formatCurrency(value) {
        return '₫' + value.toLocaleString('vi-VN');
    }

    // Hiển thị/ẩn tùy chỉnh ngày
    document.getElementById('dateRange').addEventListener('change', function() {
        const customDateRange = document.getElementById('customDateRange');
        const customDateRangeEnd = document.getElementById('customDateRangeEnd');
        if (this.value === 'custom') {
            customDateRange.classList.remove('d-none');
            customDateRangeEnd.classList.remove('d-none');
        } else {
            customDateRange.classList.add('d-none');
            customDateRangeEnd.classList.add('d-none');
        }
    });

    // Hàm tải dữ liệu báo cáo
    function loadReportData() {
        const form = document.getElementById('reportFilterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        if (formData.get('dateRange') === 'custom') {
            params.set('startDate', document.getElementById('startDate').value);
            params.set('endDate', document.getElementById('endDate').value);
        }

        fetch(`api/report.php?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                // Cập nhật thống kê
                document.getElementById('totalRevenue').textContent = formatCurrency(data.summary.totalRevenue);
                document.getElementById('totalOrders').textContent = data.summary.totalOrders;
                document.getElementById('avgRating').textContent = data.summary.avgRating.toFixed(1);

                // Cập nhật biểu đồ doanh thu
                const revenueChart = Chart.getChart('revenueChart');
                revenueChart.data.labels = data.revenue.labels;
                revenueChart.data.datasets[0].data = data.revenue.data;
                revenueChart.update();

                // Cập nhật biểu đồ sản phẩm bán chạy
                const productChart = Chart.getChart('productChart');
                productChart.data.labels = data.topProducts.labels;
                productChart.data.datasets[0].data = data.topProducts.data;
                productChart.update();

                // Cập nhật bảng báo cáo
                const tbody = document.getElementById('reportTable');
                tbody.innerHTML = '';
                data.report.forEach(row => {
                    tbody.innerHTML += `
                        <tr class="product-row">
                            <td>${row.date}</td>
                            <td>${formatCurrency(row.revenue)}</td>
                            <td>${row.orders}</td>
                            <td>${row.productsSold}</td>
                            <td>${row.avgRating.toFixed(1)}</td>
                        </tr>
                    `;
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể tải dữ liệu báo cáo!'
                });
            });
    }

    // Hàm xuất báo cáo chi tiết
    function exportDetailedReport() {
        const form = document.getElementById('reportFilterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        if (formData.get('dateRange') === 'custom') {
            params.set('startDate', document.getElementById('startDate').value);
            params.set('endDate', document.getElementById('endDate').value);
        }

        Swal.fire({
            title: 'Xuất báo cáo',
            text: 'Bạn muốn xuất báo cáo dưới dạng PDF hay Excel?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'PDF',
            cancelButtonText: 'Excel'
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = `api/export_report.php?format=pdf&${params.toString()}`;
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = `api/export_report.php?format=excel&${params.toString()}`;
            }
        });
    }

    // Xử lý sự kiện submit form lọc
    document.getElementById('reportFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadReportData();
    });

    // Tải dữ liệu khi trang được load
    document.addEventListener('DOMContentLoaded', loadReportData);

    // Khởi tạo biểu đồ
    document.addEventListener('DOMContentLoaded', () => {
        new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Doanh thu (triệu VNĐ)',
                    data: [],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.1)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        new Chart(document.getElementById('productChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Số lượng bán',
                    data: [],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#6f42c1'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    });
</script>