<?php
require_once '../config/database-connection.php';
require_once __DIR__ . '/../config/phppdf/fpdf.php';

$emp_code = $_GET['employee_code'] ?? null;
if (!$emp_code) exit('Employee not found');

$stmt = $conn->prepare("SELECT * FROM tbl_employee WHERE employee_code = ?");
$stmt->bind_param("s", $emp_code);
$stmt->execute();
$employee = $stmt->get_result()->fetch_assoc();
if (!$employee) exit('Employee not found');

$qrPath = __DIR__ . "/../images/qr-codes/" . $employee['employee_code'] . ".png";

$pdf = new FPDF();
$pdf->AddPage();

// ----------------------
// HEADER BANNER (CENTERED, LOGO ON LEFT, SOLID COFFEE COLOR)
// ----------------------
$pageW = $pdf->GetPageWidth();
$headerH = 50;

// Solid coffee background
$pdf->SetFillColor(111, 78, 55); // deep coffee
$pdf->Rect(0, 0, $pageW, $headerH, 'F');

// Logo on left
$logoPath = __DIR__ . '/../images/mglogo.PNG';
$logoSize = 28;
$logoMargin = 10;
if(file_exists($logoPath)){
    $pdf->Image($logoPath, $logoMargin, ($headerH - $logoSize)/2, $logoSize, $logoSize);
}

// Vertical center for title & info
$headerCenterY = $headerH / 2;

// Company Title: MG CAFE
$pdf->SetFont('Arial','B',30);
$pdf->SetTextColor(255, 245, 230); // soft cream
$pdf->SetY($headerCenterY - 10);
$pdf->Cell(0,10,"MG CAFE",0,1,'C');

// Company Info: address & contact
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(255, 245, 230);
$pdf->Cell(0,6,"Julian St, Brgy San Roque, Cardona, Rizal, Philippines, 1940",0,1,'C');
$pdf->Cell(0,6,"Contact: 09090909090 | Email: mgcafe2025@gmail.com",0,1,'C');

// Accent line below header
$pdf->SetDrawColor(160, 123, 90); // warm caramel line
$pdf->SetLineWidth(0.5);
$pdf->Line(0, $headerH, $pageW, $headerH);

$pdf->Ln(25);

// ----------------------
// TITLE: EMPLOYEE PROFILE
// ----------------------
$pdf->SetFont('Arial','B',24);
$pdf->SetTextColor(95, 62, 43);
$pdf->Cell(0,15,"EMPLOYEE PROFILE",0,1,'C');
$pdf->Ln(5);

// ----------------------
// EMPLOYEE INFO TABLE
// ----------------------
$labelW = 60;
$valueW = 130;
$rowH   = 12;

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

// Alternating warm beige table rows
$bgColors = [
    [255,248,240], // light cream
    [245,241,237]  // slightly darker cream
];

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
// QR CODE CUT GUIDE
// ----------------------
if (file_exists($qrPath)) {
    $pdf->SetFont('Arial','',11);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0,8,"Cut along the border below to detach QR code:",0,1,'C');

    $qrSize = 40;
    $x = ($pdf->GetPageWidth() - $qrSize) / 2;
    $y = $pdf->GetY() + 8;

    $padding = 6;
    $pdf->SetFillColor(255, 248, 240); // match table row
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

// Draw footer rectangle only (no text)
$pdf->SetY(-$footerH);
$pdf->SetDrawColor(0,0,0); // optional border color
$pdf->SetFillColor(111, 78, 55); // coffee brown
$pdf->Rect(0, $pdf->GetY(), $pageW, $footerH, 'F');

// OUTPUT
$fileName = $employee['first_name'] . '_' . $employee['last_name'] . '.pdf';
$pdf->Output('D', $fileName);
exit;
?>
