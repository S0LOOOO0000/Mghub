<?php
require_once __DIR__ . '/../config/database-connection.php';
require_once __DIR__ . '/../config/phpqrcode/qrlib.php';
require_once __DIR__ . '/../config/phppdf/fpdf.php';

// Define station colors for guide-cut (RGB)
$stationColors = [
    'Cafe' => [183, 123, 87],        // warm brown
    'Spa' => [0, 150, 136],          // teal
    'Hub' => [233, 30, 99] // pink
];

// QR & layout settings
$qrSize = 50;       // QR size in mm
$margin = 10;       // page margin
$padding = 20;      // space between QR codes
$codesPerRow = 3;   // 3 QR codes per row
$nameGap = 8;       // gap between guide-cut and name
$xStart = $margin;
$yStart = $margin;

// PDF setup
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetAutoPageBreak(true, 10);

// Stations order
$stations = ['Cafe', 'Spa', 'Hub'];
$shifts = ['Morning', 'Mid', 'Night', 'Fixed'];

foreach ($stations as $station) {
    // Add station header at top of page
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor($stationColors[$station][0], $stationColors[$station][1], $stationColors[$station][2]);
    $pdf->Cell(0, 10, strtoupper($station) . ' EMPLOYEES', 0, 1, 'C');
    $pdf->Ln(5);

    // Reset position
    $x = $xStart;
    $y = $yStart + 15; // leave space for header
    $count = 0;

    // Fetch employees by station, sorted by shift and name
    $stmt = $conn->prepare("
        SELECT * FROM tbl_employee
        WHERE work_station = ?
        ORDER BY FIELD(shift, 'Morning','Mid','Night','Fixed'), first_name, last_name
    ");
    $stmt->bind_param("s", $station);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($employee = $result->fetch_assoc()) {
        // Generate QR code image
        $qrFile = sys_get_temp_dir() . '/' . $employee['employee_code'] . '.png';
        QRcode::png($employee['employee_code'], $qrFile, QR_ECLEVEL_L, 3);

        // Draw cut guide (dashed box) with station color
        $color = $stationColors[$station];
        $pdf->SetDrawColor($color[0], $color[1], $color[2]);
        $pdf->SetLineWidth(0.4);
        $dash = 2; $gap = 2;
        $boxPadding = 3;

        // Top & Bottom
        for ($i = $x - $boxPadding; $i <= $x - $boxPadding + $qrSize + $boxPadding*2; $i += $dash+$gap) {
            $pdf->Line($i, $y - $boxPadding, min($i+$dash, $x - $boxPadding + $qrSize + $boxPadding*2), $y - $boxPadding); // top
            $pdf->Line($i, $y - $boxPadding + $qrSize + $boxPadding*2, min($i+$dash, $x - $boxPadding + $qrSize + $boxPadding*2), $y - $boxPadding + $qrSize + $boxPadding*2); // bottom
        }
        // Left & Right
        for ($j = $y - $boxPadding; $j <= $y - $boxPadding + $qrSize + $boxPadding*2; $j += $dash+$gap) {
            $pdf->Line($x - $boxPadding, $j, $x - $boxPadding, min($j+$dash, $y - $boxPadding + $qrSize + $boxPadding*2)); // left
            $pdf->Line($x - $boxPadding + $qrSize + $boxPadding*2, $j, $x - $boxPadding + $qrSize + $boxPadding*2, min($j+$dash, $y - $boxPadding + $qrSize + $boxPadding*2)); // right
        }

        // Draw QR code
        $pdf->Image($qrFile, $x, $y, $qrSize, $qrSize);

        // Employee Name below QR with gap
        $pdf->SetXY($x, $y + $qrSize + $boxPadding*2 + $nameGap);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0); // name in black
        $pdf->Cell($qrSize, 5, $employee['first_name'].' '.$employee['last_name'], 0, 0, 'C');

        // Move to next position
        $count++;
        if ($count % $codesPerRow == 0) {
            $x = $xStart;
            $y += $qrSize + $boxPadding*2 + $nameGap + $padding;
        } else {
            $x += $qrSize + $padding;
        }

        // New page if exceeded
        if ($y + $qrSize + $boxPadding*2 + $nameGap + $padding > $pdf->GetPageHeight() - $margin) {
            $pdf->AddPage();
            $x = $xStart;
            $y = $yStart + 15;
        }

        unlink($qrFile);
    }
}

$pdf->Output('D', 'MGHub_All_QRCodes.pdf');
?>
