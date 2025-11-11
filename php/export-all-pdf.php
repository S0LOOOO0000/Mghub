<?php
require_once __DIR__ . '/../config/database-connection.php';
require_once __DIR__ . '/../config/phppdf/fpdf.php';

// Stations order
$stations = ['Cafe', 'Spa', 'Hub'];

// PDF setup
$pdf = new FPDF();
$pdf->SetAutoPageBreak(true, 15);

// Table and QR settings
$labelW = 60;
$valueW = 130;
$rowH   = 12;
$qrSize = 40;
$padding = 6; // cut guide padding

// Alternating warm beige table rows
$bgColors = [
    [255,248,240], // light cream
    [245,241,237]  // slightly darker cream
];

// Function to add employee info row
function addRow($pdf, $label, $value, $labelW, $valueW, $rowH, $bgColor) {
    $pdf->SetDrawColor(160, 123, 80); // coffee border
    $pdf->SetLineWidth(0.2);

    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(...$bgColor);
    $pdf->SetTextColor(70, 50, 35);
    $pdf->Cell($labelW,$rowH,$label,1,0,'C',true);

    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(90, 64, 51);
    $pdf->Cell($valueW,$rowH,$value,1,1,'C',true);
}

foreach ($stations as $station) {
    $stmt = $conn->prepare("SELECT * FROM tbl_employee WHERE work_station = ? ORDER BY FIELD(shift,'Morning','Mid','Night','Fixed'), first_name, last_name");
    $stmt->bind_param("s", $station);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($employee = $result->fetch_assoc()) {
        $pdf->AddPage();
        $pageW = $pdf->GetPageWidth();
        $headerH = 50;

        // ----------------------
        // HEADER
        // ----------------------
        $pdf->SetFillColor(111, 78, 55); // deep coffee
        $pdf->Rect(0, 0, $pageW, $headerH, 'F');

        // Logo on left
        $logoPath = __DIR__ . '/../images/mglogo.PNG';
        $logoSize = 28;
        $logoMargin = 10;
        if(file_exists($logoPath)){
            $pdf->Image($logoPath, $logoMargin, ($headerH - $logoSize)/2, $logoSize, $logoSize);
        }

        // Centered title & info
        $headerCenterY = $headerH / 2;
        $pdf->SetFont('Arial','B',30);
        $pdf->SetTextColor(255, 245, 230);
        $pdf->SetY($headerCenterY - 10);
        $pdf->Cell(0,10,"MG CAFE",0,1,'C');

        $pdf->SetFont('Arial','',12);
        $pdf->SetTextColor(255, 245, 230);
        $pdf->Cell(0,6,"Julian St, Brgy San Roque, Cardona, Rizal, Philippines, 1940",0,1,'C');
        $pdf->Cell(0,6,"Contact: 09090909090 | Email: mgcafe2025@gmail.com",0,1,'C');

        // Accent line
        $pdf->SetDrawColor(160, 123, 90);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(0, $headerH, $pageW, $headerH);

        $pdf->Ln(25);

        // ----------------------
        // EMPLOYEE PROFILE TITLE
        // ----------------------
        $pdf->SetFont('Arial','B',24);
        $pdf->SetTextColor(95, 62, 43);
        $pdf->Cell(0,15,"EMPLOYEE PROFILE",0,1,'C');
        $pdf->Ln(5);

        // ----------------------
        // EMPLOYEE INFO TABLE
        // ----------------------
        $fields = [
            "Employee Code"=>$employee['employee_code'],
            "Full Name"=>$employee['first_name']." ".$employee['last_name'],
            "Email"=>$employee['email_address'],
            "Contact"=>$employee['contact_number'],
            "Work Station"=>$employee['work_station'],
            "Role"=>$employee['role'],
            "Shift"=>$employee['shift']
        ];

        $i = 0;
        foreach($fields as $label=>$value){
            addRow($pdf, $label, $value, $labelW, $valueW, $rowH, $bgColors[$i%2]);
            $i++;
        }

        $pdf->Ln(15);

        // ----------------------
        // QR CODE
        // ----------------------
        $qrPath = __DIR__ . "/../images/qr-codes/" . $employee['employee_code'] . ".png";
        if (file_exists($qrPath)) {
            $pdf->SetFont('Arial','',11);
            $pdf->SetTextColor(100, 100, 100);
            $pdf->Cell(0,8,"Cut along the border below to detach QR code:",0,1,'C');

            $x = ($pdf->GetPageWidth() - $qrSize) / 2;
            $y = $pdf->GetY() + 8;
            $pdf->SetFillColor(255, 248, 240);
            $pdf->Rect($x-$padding, $y-$padding, $qrSize+($padding*2), $qrSize+($padding*2), 'F');
            $pdf->Image($qrPath, $x, $y, $qrSize, $qrSize);
            $pdf->Ln($qrSize + ($padding*2) + 10);
        } else {
            $pdf->SetFont('Arial','I',11);
            $pdf->SetTextColor(141, 123, 112);
            $pdf->Cell(0,8,"QR Code not available.",0,1,'C');
        }

        // ----------------------
        // FOOTER
        // ----------------------
        $footerH = 15;
        $pdf->SetY(-$footerH);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(111, 78, 55);
        $pdf->Rect(0, $pdf->GetY(), $pageW, $footerH, 'F');
    }
}

// OUTPUT PDF
$pdf->Output('D','MGHub_All_Employees.pdf');
?>
