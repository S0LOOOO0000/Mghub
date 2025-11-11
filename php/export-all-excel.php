<?php
require_once __DIR__ . '/../config/database-connection.php';
require_once __DIR__ . '/../config/phpexcel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Initialize spreadsheet
$spreadsheet = new Spreadsheet();

// Column headers
$columns = ['ID', 'First Name', 'Last Name', 'Email', 'Contact', 'Station', 'Role', 'Shift', 'Status'];

// Convert RGB array to HEX string
function rgbToHex($rgb) {
    return sprintf("%02X%02X%02X", $rgb[0], $rgb[1], $rgb[2]);
}

// Color scheme for stations (HEX will be generated)
$stationColors = [
    'All Station' => ['title' => [79, 129, 189], 'header' => [198, 224, 255]],      // Blue tones
    'Cafe' => ['title' => [183, 123, 87], 'header' => [239, 222, 205]],             // Warm brown tones
    'Spa' => ['title' => [0, 150, 136], 'header' => [204, 242, 238]],               // Teal tones
    'Hub' => ['title' => [233, 30, 99], 'header' => [255, 204, 221]]      // Pink tones
];

function styleSheet($sheet, $highestRow, $titleColorRGB, $headerColorRGB) {
    $titleColor = rgbToHex($titleColorRGB);
    $headerColor = rgbToHex($headerColorRGB);

    // Style sheet title (top label)
    $sheet->getStyle('A1:I1')->getFont()->setBold(true)->setSize(20);
    $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1:I1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A1:I1')->getFill()->setFillType(Fill::FILL_SOLID)
          ->getStartColor()->setRGB($titleColor);

    // Style headers (column labels)
    $sheet->getStyle('A2:I2')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A2:I2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('A2:I2')->getFill()->setFillType(Fill::FILL_SOLID)
          ->getStartColor()->setRGB($headerColor);

    // Borders for headers and data
    $sheet->getStyle("A2:I$highestRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Auto width for all columns
    foreach(range('A','I') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Left alignment for all data cells
    $sheet->getStyle("A3:I$highestRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle("A3:I$highestRow")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
}

// Stations and sheets
$stations = [
    'All Station' => null,
    'Cafe' => 'Cafe',
    'Spa' => 'Spa',
    'Hub' => 'Hub'
];

foreach ($stations as $sheetName => $station) {
    if ($station === null) {
        $stmt = $conn->prepare("SELECT * FROM tbl_employee ORDER BY work_station, FIELD(shift,'Morning','Mid','Night','Fixed'), first_name, last_name");
    } else {
        $stmt = $conn->prepare("SELECT * FROM tbl_employee WHERE work_station = ? ORDER BY FIELD(shift,'Morning','Mid','Night','Fixed'), first_name, last_name");
        $stmt->bind_param("s", $station);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // Create or select sheet
    if ($sheetName === 'All Station') {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($sheetName);
    } else {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle($sheetName);
    }

    // Sheet title
    $title = $sheetName === 'All Station' ? 'MG CAFE' : strtoupper($sheetName);
    $sheet->setCellValue('A1', $title);
    $sheet->mergeCells('A1:I1');

    // Header row
    $col = 'A';
    foreach ($columns as $header) {
        $sheet->setCellValue($col.'2', $header);
        $col++;
    }

    // Data rows
    $rowNum = 3;
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A'.$rowNum, $row['employee_code']);
        $sheet->setCellValue('B'.$rowNum, $row['first_name']);
        $sheet->setCellValue('C'.$rowNum, $row['last_name']);
        $sheet->setCellValue('D'.$rowNum, $row['email_address']);
        $sheet->setCellValue('E'.$rowNum, $row['contact_number']);
        $sheet->setCellValue('F'.$rowNum, $row['work_station']);
        $sheet->setCellValue('G'.$rowNum, $row['role']);
        $sheet->setCellValue('H'.$rowNum, $row['shift']);
        $sheet->setCellValue('I'.$rowNum, $row['status']);
        $rowNum++;
    }

    // Apply styling with aesthetic colors
    styleSheet($sheet, $rowNum-1, $stationColors[$sheetName]['title'], $stationColors[$sheetName]['header']);
}

// Output Excel
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="MGHub_All_Employees_Styled.xlsx"');
$writer->save('php://output');
exit;
?>
