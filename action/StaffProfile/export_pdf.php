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
        $this->Cell(0, 10, 'Staff List ', 0, false, 'C', 0);
        $this->Ln(8);
        
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
$pdf->SetTitle('Staff List');

// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Set margins
$pdf->SetMargins(15, 50, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 15);

// Add a page
$pdf->AddPage();

// Get staff data
$sql = "SELECT hrstaffprofile.EmpCode, 
                    hrstaffprofile.EmpName, 
                    hrcompany.Description as CompanyName,
                    hrposition.Description as PositionName,
                    hrdepartment.Description as DepartmentName,
                    hrdivision.Description as DivisionName,
                    hrstaffprofile.StartDate, 
                    hrstaffprofile.Status, 
                    hrstaffprofile.Contact
                    FROM hrstaffprofile
                    LEFT JOIN hrcompany ON hrstaffprofile.Company = hrcompany.Code
                    LEFT JOIN hrdepartment ON hrstaffprofile.Department = hrdepartment.Code
                    LEFT JOIN hrdivision ON hrstaffprofile.Division = hrdivision.Code
                    LEFT JOIN hrposition ON hrstaffprofile.Position = hrposition.Code
                    ORDER BY EmpCode DESC";
$result = $con->query($sql);

// Set font for table header
$pdf->SetFont('helvetica', 'B', 10);

// Define table header colors
$pdf->SetFillColor(243, 244, 246);
$pdf->SetTextColor(31, 41, 55);

// Table headers
$headers = array('Employee Code', 'Full Name', 'Company', 'Position', 'Department', 'Division', 'Start Date', 'Status', 'Contact');
$w = array(25, 35, 30, 30, 30, 30, 25, 20, 30); // Column widths

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
        $pdf->Cell($w[0], 8, $row['EmpCode'], 'LR', 0, 'L', $fill);
        $pdf->Cell($w[1], 8, $row['EmpName'], 'LR', 0, 'L', $fill);
        $pdf->Cell($w[2], 8, $row['CompanyName'], 'LR', 0, 'L', $fill);
        $pdf->Cell($w[3], 8, $row['PositionName'], 'LR', 0, 'L', $fill);
        $pdf->Cell($w[4], 8, $row['DepartmentName'], 'LR', 0, 'L', $fill);
        $pdf->Cell($w[5], 8, $row['DivisionName'], 'LR', 0, 'L', $fill);
        $pdf->Cell($w[6], 8, $row['StartDate'], 'LR', 0, 'L', $fill);
        
        // Style the status
        if ($row['Status'] == 'Active') {
            $pdf->SetTextColor(5, 150, 105);
        } else {
            $pdf->SetTextColor(220, 38, 38);
        }
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell($w[7], 8, $row['Status'], 'LR', 0, 'L', $fill);
        
        // Reset text color and font
        $pdf->SetTextColor(0);
        $pdf->SetFont('helvetica', '', 9);
        
        $pdf->Cell($w[8], 8, $row['Contact'], 'LR', 0, 'L', $fill);
        $pdf->Ln();
        $fill = !$fill;
    }
    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
}

// Close and output PDF document
$pdf->Output('Staff_List_' . date('Y-m-d') . '.pdf', 'D');

$con->close();
?>
