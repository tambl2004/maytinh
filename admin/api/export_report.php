<?php
require_once '../../vendor/autoload.php'; // Đảm bảo cài đặt TCPDF và PhpSpreadsheet qua Composer
include '../../config/connect.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

// Hàm validate ngày
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Lấy tham số từ request
$format = isset($_GET['format']) ? $_GET['format'] : 'pdf';
$dateRange = isset($_GET['dateRange']) ? $_GET['dateRange'] : '7days';
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;

// Xác định khoảng thời gian
$today = date('Y-m-d');
if ($dateRange === '7days') {
    $start = date('Y-m-d', strtotime('-6 days', strtotime($today)));
    $end = $today;
} elseif ($dateRange === '30days') {
    $start = date('Y-m-d', strtotime('-29 days', strtotime($today)));
    $end = $today;
} elseif ($dateRange === 'custom' && validateDate($startDate) && validateDate($endDate)) {
    $start = $startDate;
    $end = $endDate;
} else {
    $start = date('Y-m-d', strtotime('-6 days', strtotime($today)));
    $end = $today;
}

// Chuẩn bị câu truy vấn
$whereClause = "WHERE donhang.ngaytao BETWEEN '$start 00:00:00' AND '$end 23:59:59'";
if ($categoryId > 0) {
    $whereClause .= " AND sanpham.danhmuc_id = $categoryId";
}

// Lấy dữ liệu báo cáo
$reportQuery = "
    SELECT 
        DATE(donhang.ngaytao) as date,
        SUM(donhang.tienthucte) as revenue,
        COUNT(DISTINCT donhang.id) as orders,
        SUM(chitietdonhang.soluong) as productsSold,
        AVG(danhgia.diemso) as avgRating
    FROM donhang
    LEFT JOIN chitietdonhang ON donhang.id = chitietdonhang.donhang_id
    LEFT JOIN sanpham ON chitietdonhang.sanpham_id = sanpham.id
    LEFT JOIN danhgia ON sanpham.id = danhgia.sanpham_id
    $whereClause
    GROUP BY DATE(donhang.ngaytao)
    ORDER BY DATE(donhang.ngaytao)
";
$reportResult = mysqli_query($conn, $reportQuery);
$report = [];
while ($row = mysqli_fetch_assoc($reportResult)) {
    $report[] = [
        'date' => date('d/m/Y', strtotime($row['date'])),
        'revenue' => (float)$row['revenue'],
        'orders' => (int)$row['orders'],
        'productsSold' => (int)$row['productsSold'],
        'avgRating' => $row['avgRating'] ? (float)$row['avgRating'] : 0
    ];
}

// Xuất báo cáo
if ($format === 'pdf') {
    // Khởi tạo TCPDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('MayTinhPro');
    $pdf->SetTitle('Báo Cáo Thống Kê');
    $pdf->SetHeaderData('', 0, 'Báo Cáo Thống Kê', 'Từ ' . $start . ' đến ' . $end);
    $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
    $pdf->SetMargins(15, 20, 15);
    $pdf->SetAutoPageBreak(true, 25);
    $pdf->AddPage();

    // Nội dung PDF
    $html = '
    <h1 style="text-align: center;">Báo Cáo Thống Kê</h1>
    <p style="text-align: center;">Từ ' . $start . ' đến ' . $end . '</p>
    <table border="1" cellpadding="5">
        <thead>
            <tr style="background-color: #3b82f6; color: #ffffff;">
                <th>Ngày</th>
                <th>Doanh thu</th>
                <th>Số đơn hàng</th>
                <th>Sản phẩm bán ra</th>
                <th>Đánh giá trung bình</th>
            </tr>
        </thead>
        <tbody>';
    foreach ($report as $row) {
        $html .= '
            <tr>
                <td>' . $row['date'] . '</td>
                <td>₫' . number_format($row['revenue'], 0, ',', '.') . '</td>
                <td>' . $row['orders'] . '</td>
                <td>' . $row['productsSold'] . '</td>
                <td>' . number_format($row['avgRating'], 1) . '</td>
            </tr>';
    }
    $html .= '</tbody></table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('bao_cao_thong_ke.pdf', 'D');

} elseif ($format === 'excel') {
    // Khởi tạo PhpSpreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Báo Cáo Thống Kê');

    // Tiêu đề
    $sheet->setCellValue('A1', 'Báo Cáo Thống Kê');
    $sheet->setCellValue('A2', 'Từ ' . $start . ' đến ' . $end);
    $sheet->mergeCells('A1:E1');
    $sheet->mergeCells('A2:E2');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A2')->getFont()->setSize(12);

    // Tiêu đề cột
    $sheet->setCellValue('A4', 'Ngày');
    $sheet->setCellValue('B4', 'Doanh thu');
    $sheet->setCellValue('C4', 'Số đơn hàng');
    $sheet->setCellValue('D4', 'Sản phẩm bán ra');
    $sheet->setCellValue('E4', 'Đánh giá trung bình');
    $sheet->getStyle('A4:E4')->getFont()->setBold(true);
    $sheet->getStyle('A4:E4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3b82f6');

    // Dữ liệu
    $rowNumber = 5;
    foreach ($report as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row['date']);
        $sheet->setCellValue('B' . $rowNumber, $row['revenue']);
        $sheet->setCellValue('C' . $rowNumber, $row['orders']);
        $sheet->setCellValue('D' . $rowNumber, $row['productsSold']);
        $sheet->setCellValue('E' . $rowNumber, $row['avgRating']);
        $rowNumber++;
    }

    // Định dạng cột
    $sheet->getStyle('B5:B' . ($rowNumber - 1))->getNumberFormat()->setFormatCode('#,##0');
    $sheet->getStyle('E5:E' . ($rowNumber - 1))->getNumberFormat()->setFormatCode('0.0');
    foreach (range('A', 'E') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    // Xuất file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="bao_cao_thong_ke.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
}

mysqli_close($conn);
?>