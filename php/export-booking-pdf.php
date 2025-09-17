<?php
require_once __DIR__ . '/../config/phppdf/fpdf.php';

// Get parameters from URL
$customerName    = isset($_GET['name']) ? $_GET['name'] : 'Customer';
$customerEmail   = isset($_GET['email']) ? $_GET['email'] : 'N/A';
$customerContact = isset($_GET['contact']) ? $_GET['contact'] : 'N/A';
$eventName       = isset($_GET['event']) ? $_GET['event'] : 'Event';
$eventDate       = isset($_GET['date']) ? $_GET['date'] : 'N/A';
$eventTime       = isset($_GET['time']) ? $_GET['time'] : 'N/A';
$eventStatus     = isset($_GET['status']) ? $_GET['status'] : 'Pending';
$eventDesc       = isset($_GET['description']) ? $_GET['description'] : 'No description provided.';

// ===== Format Date =====
$eventDateFormatted = 'N/A';
if ($eventDate && strtotime($eventDate) !== false) {
    $eventDateFormatted = date("F j, Y", strtotime($eventDate)); // e.g., September 16, 2025
}

// ===== Format Time =====
$eventTimeFormatted = 'N/A';
if ($eventTime && strtotime($eventTime) !== false) {
    $eventTimeFormatted = date("g:i A", strtotime($eventTime)); // e.g., 2:30 PM
}

// ===== PDF Setup =====
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pageW = $pdf->GetPageWidth();
$headerH = 50;

// ===== HEADER =====
$pdf->SetFillColor(111, 78, 55);
$pdf->Rect(0, 0, $pageW, $headerH, 'F');

$logoPath = __DIR__ . '/../images/mglogo.PNG';
$logoSize = 28;
$logoMargin = 10;
if(file_exists($logoPath)){
    $pdf->Image($logoPath, $logoMargin, ($headerH - $logoSize)/2, $logoSize, $logoSize);
}

$headerCenterY = $headerH / 2;
$pdf->SetFont('Arial','B',30);
$pdf->SetTextColor(255, 245, 230);
$pdf->SetY($headerCenterY - 10);
$pdf->Cell(0,10,"MG CAFE",0,1,'C');

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,6,"Julian St, Brgy San Roque, Cardona, Rizal, Philippines, 1940",0,1,'C');
$pdf->Cell(0,6,"Contact: 09090909090 | Email: mgcafe2025@gmail.com",0,1,'C');

$pdf->SetDrawColor(160, 123, 90);
$pdf->SetLineWidth(0.5);
$pdf->Line(0, $headerH, $pageW, $headerH);

$pdf->Ln(25);

// ===== TITLE =====
$pdf->SetFont('Arial','B',24);
$pdf->SetTextColor(95, 62, 43);
$pdf->Cell(0,15,"EVENT RESERVATION",0,1,'C');
$pdf->Ln(5);

// ===== EVENT INFO TABLE =====
$labelW = 60;
$valueW = 130;
$rowH   = 12;

function addRow($pdf, $label, $value, $labelW, $valueW, $rowH, $bgColor){
    $pdf->SetDrawColor(160,123,80);
    $pdf->SetLineWidth(0.2);

    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(...$bgColor);
    $pdf->SetTextColor(70,50,35);
    $pdf->Cell($labelW,$rowH,$label,1,0,'C',true);

    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(90,64,51);
    $pdf->Cell($valueW,$rowH,$value,1,1,'C',true);
}

// Alternating warm beige colors
$bgColors = [
    [255,248,240],
    [245,241,237]
];

// Fields in exact order
$fields = [
    "Event" => $eventName,
    "Customer" => $customerName,
    "Email" => $customerEmail,
    "Contact" => $customerContact,
    "Date" => $eventDateFormatted,
    "Time" => $eventTimeFormatted,
    "Status" => $eventStatus
];

$i = 0;
foreach($fields as $label=>$value){
    addRow($pdf, $label, $value, $labelW, $valueW, $rowH, $bgColors[$i%2]);
    $i++;
}

$pdf->Ln(10);

// ===== EVENT DESCRIPTION =====
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,"Event Description",0,1,'L');
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(0,8,$eventDesc,1,'L');
$pdf->Ln(15);

// ===== FOOTER =====
$footerH = 15;
$pdf->SetY(-$footerH);
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(111,78,55);
$pdf->Rect(0,$pdf->GetY(),$pageW,$footerH,'F');

// OUTPUT
$fileName = "Reservation_".preg_replace('/\s+/','_',$eventName).".pdf";
$pdf->Output('D',$fileName);
exit;
?>
