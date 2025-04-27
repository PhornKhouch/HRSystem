<?php
require '../../Config/conect.php';
require '../../vendor/tecnickcom/tcpdf/tcpdf.php';

// Extend TCPDF to create custom header and footer
class MYPDF extends TCPDF {
    public function Header() {
        // Set font
        $this->SetFont('helvetica', 'B', 22);
        
        // Title
        $this->Cell(0, 15, 'CLUBCODE-HR', 0, false, 'C', 0);
        $this->Ln(10);
        
        // Subtitle
        $this->SetFont('helvetica', '', 14);
        $this->Cell(0, 10, 'Career History List', 0, false, 'C', 0);
        $this->Ln(15);
    }

    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetFont('helvetica', '', 8);
        
        // Generation time
        $this->Cell(0, 10, 'Generated: ' . date('F j, Y g:i A'), 0, false, 'L', 0);
        
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0);
    }
}

// Create new PDF document
$pdf = new MYPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('CLUBCODE-HR');
$pdf->SetAuthor('CLUBCODE-HR');
$pdf->SetTitle('Career History List');

// Set margins
$pdf->SetMargins(15, 50, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 15);

// Add a page
$pdf->AddPage();

// Get career history data
$sql = "SELECT ch.*, sp.EmpName 
        FROM careerhistory ch 
        LEFT JOIN hrstaffprofile sp ON ch.EmployeeID = sp.EmpCode 
        ORDER BY ch.CreatedAt DESC";
$result = $con->query($sql);

// Set font for table header
$pdf->SetFont('helvetica', 'B', 10);

// Define table header colors
$pdf->SetFillColor(243, 244, 246);
$pdf->SetTextColor(31, 41, 55);

// Table headers and column widths
$headers = array('Career', 'ID', 'Name', 'Position', 'Department', 'Effective Date', 'Resignation Date', 'Remark', 'Increase');
$w = array(25, 25, 35, 35, 35, 25, 25, 30, 25); // Column widths adjusted for landscape mode

// Print table header
foreach($headers as $i => $header) {
    $pdf->Cell($w[$i], 10, $header, 1, 0, 'L', true);
}
$pdf->Ln();

// Set font for table data
$pdf->SetFont('helvetica', '', 9);

// Reset colors
$pdf->SetFillColor(250, 250, 250);
$pdf->SetTextColor(0);

// Print table data
if ($result && $result->num_rows > 0) {
    $fill = false;
    while ($row = $result->fetch_assoc()) {
        // Career Type
        $pdf->Cell($w[0], 8, $row['CareerHistoryType'], 'LR', 0, 'L', $fill);
        
        // Employee ID
        $pdf->Cell($w[1], 8, $row['EmployeeID'], 'LR', 0, 'L', $fill);
        
        // Name
        $pdf->Cell($w[2], 8, $row['EmpName'], 'LR', 0, 'L', $fill);
        
        // Position
        $pdf->Cell($w[3], 8, $row['PositionTitle'], 'LR', 0, 'L', $fill);
        
        // Department
        $pdf->Cell($w[4], 8, $row['Department'], 'LR', 0, 'L', $fill);
        
        // Start Date
        $pdf->Cell($w[5], 8, date('d M Y', strtotime($row['StartDate'])), 'LR', 0, 'L', $fill);
        
        // End Date
        $pdf->Cell($w[6], 8, $row['EndDate'] ? date('d M Y', strtotime($row['EndDate'])) : '-', 'LR', 0, 'L', $fill);
        
        // Remark
        $pdf->Cell($w[7], 8, $row['Remark'] ?? '-', 'LR', 0, 'L', $fill);
        
        // Increase
        $pdf->Cell($w[8], 8, $row['Increase'] ? number_format($row['Increase'], 2) : '-', 'LR', 0, 'L', $fill);
        
        $pdf->Ln();
        $fill = !$fill;
    }
    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
} else {
    $pdf->Cell(array_sum($w), 10, 'No data found', 1, 0, 'C');
}

// Close and output PDF document
$pdf->Output('Career_History_' . date('Y-m-d') . '.pdf', 'D');

$con->close();
?>
