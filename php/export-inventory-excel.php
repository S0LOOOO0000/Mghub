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
$columns = ['No.', 'Item Name', 'Quantity', 'Category', 'Status', 'Date Added'];

// Convert RGB to HEX
function rgbToHex($rgb) {
    return sprintf("%02X%02X%02X", $rgb[0], $rgb[1], $rgb[2]);
}

// Branch colors
$branchColors = [
    'All' => ['title' => [79, 129, 189], 'header' => [198, 224, 255]],
    'MG Cafe' => ['title' => [79, 129, 189], 'header' => [198, 224, 255]],
    'MG Hub'  => ['title' => [233, 30, 99], 'header' => [255, 204, 221]],
    'MG Spa'  => ['title' => [0, 150, 136], 'header' => [204, 242, 238]]
];

// Style function
function styleSheet($sheet, $highestRow, $titleColorRGB, $headerColorRGB) {
    $titleColor = rgbToHex($titleColorRGB);
    $headerColor = rgbToHex($headerColorRGB);

    // Sheet title
    $sheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(20);
    $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1:F1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('A1:F1')->getFill()->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setRGB($titleColor);

    // Headers
    $sheet->getStyle('A2:F2')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A2:F2')->getFill()->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setRGB($headerColor);

    // Borders
    $sheet->getStyle("A2:F$highestRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Auto width
    foreach(range('A','F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Data alignment
    $sheet->getStyle("A3:F$highestRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("A3:F$highestRow")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
}

// Branches / sheets
$branches = ['All', 'MG Cafe', 'MG Hub', 'MG Spa'];

foreach ($branches as $sheetName) {
    if ($sheetName === 'All') {
        $stmt = $conn->prepare("SELECT * FROM tbl_inventory ORDER BY branch, item_category, item_name");
    } else {
        $stmt = $conn->prepare("SELECT * FROM tbl_inventory WHERE branch = ? ORDER BY item_category, item_name");
        $stmt->bind_param("s", $sheetName);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // Create or select sheet
    if ($sheetName === 'All') {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('All Inventory');
    } else {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle($sheetName);
    }

    // Sheet title
    $title = $sheetName === 'All' ? 'ALL INVENTORY' : strtoupper($sheetName);
    $sheet->setCellValue('A1', $title);
    $sheet->mergeCells('A1:F1');

    // Header row
    $col = 'A';
    foreach ($columns as $header) {
        $sheet->setCellValue($col.'2', $header);
        $col++;
    }

    // Data rows
    $rowNum = 3;
    $itemNo = 1;

    // Counters for summary
    $totalItems = 0;
    $totalInStock = 0;
    $totalLowStock = 0;
    $totalOutStock = 0;

    while ($row = $result->fetch_assoc()) {
        $qty = (int)$row['item_quantity'];
        if ($qty <= 0) {
            $status = "Out of Stock";
            $totalOutStock++;
        } elseif ($qty <= 10) {
            $status = "Low Stock";
            $totalLowStock++;
        } else {
            $status = "In Stock";
            $totalInStock++;
        }

        $sheet->setCellValue('A'.$rowNum, $itemNo++);
        $sheet->setCellValue('B'.$rowNum, $row['item_name']);
        $sheet->setCellValue('C'.$rowNum, $row['item_quantity']);
        $sheet->setCellValue('D'.$rowNum, $row['item_category']);
        $sheet->setCellValue('E'.$rowNum, $status);
        $sheet->setCellValue('F'.$rowNum, date("F j, Y", strtotime($row['created_at'])));

        $rowNum++;
        $totalItems++;
    }

    // Add summary row labels
    $rowNum++;
    $summaryLabelRow = $rowNum;
    $sheet->setCellValue('A'.$summaryLabelRow, 'Total Item');
    $sheet->setCellValue('B'.$summaryLabelRow, 'Total In Stock');
    $sheet->setCellValue('C'.$summaryLabelRow, 'Total Low Stock');
    $sheet->setCellValue('D'.$summaryLabelRow, 'Total Out of Stock');

    // Summary numbers
    $rowNum++;
    $summaryNumberRow = $rowNum;
    $sheet->setCellValue('A'.$summaryNumberRow, $totalItems);
    $sheet->setCellValue('B'.$summaryNumberRow, $totalInStock);
    $sheet->setCellValue('C'.$summaryNumberRow, $totalLowStock);
    $sheet->setCellValue('D'.$summaryNumberRow, $totalOutStock);

    // Style sheet including data
    styleSheet($sheet, $rowNum, $branchColors[$sheetName]['title'], $branchColors[$sheetName]['header']);

    // Different background colors for each total label
    $sheet->getStyle("A$summaryLabelRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFD700'); // Gold
    $sheet->getStyle("B$summaryLabelRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('90EE90'); // Light Green
    $sheet->getStyle("C$summaryLabelRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFA07A'); // Light Salmon
    $sheet->getStyle("D$summaryLabelRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('87CEFA'); // Light Blue

    // Different background colors for summary numbers
    $sheet->getStyle("A$summaryNumberRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFE066'); // Yellow Gold
    $sheet->getStyle("B$summaryNumberRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98'); // Pale Green
    $sheet->getStyle("C$summaryNumberRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFB6B6'); // Pale Red
    $sheet->getStyle("D$summaryNumberRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ADD8E6'); // Pale Blue

    // Bold & center align
    $sheet->getStyle("A$summaryLabelRow:D$summaryNumberRow")->getFont()->setBold(true);
    $sheet->getStyle("A$summaryLabelRow:D$summaryNumberRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// Output Excel
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="MGHub_Inventory_Styled.xlsx"');
$writer->save('php://output');
exit;
?>
