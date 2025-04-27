<?php
require '../../Config/conect.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Staff List');

// Set document properties
$spreadsheet->getProperties()
    ->setCreator('HR System')
    ->setLastModifiedBy('HR System')
    ->setTitle('Staff List')
    ->setSubject('Staff List')
    ->setDescription('Staff List generated from HR System')
    ->setKeywords('staff list hr system')
    ->setCategory('HR Reports');

// Add company header
$sheet->mergeCells('A1:I1');
$sheet->mergeCells('A2:I2');
$sheet->mergeCells('A3:I3');

$sheet->setCellValue('A1', 'CLUB CODE');
$sheet->setCellValue('A2', 'Staff List');

// Style the header
$headerStyle = [
    'font' => [
        'bold' => true,
        'size' => 16,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
];
$sheet->getStyle('A1')->applyFromArray($headerStyle);

$subHeaderStyle = [
    'font' => [
        'size' => 12,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
];
$sheet->getStyle('A2:A3')->applyFromArray($subHeaderStyle);

// Add some spacing
$sheet->getRowDimension(4)->setRowHeight(10);

// Set column headers
$headers = ['Employee Code', 'Full Name', 'Company', 'Position', 'Department', 'Division', 'Start Date', 'Status', 'Contact'];
$col = 'A';
$row = 5;

foreach ($headers as $header) {
    $sheet->setCellValue($col . $row, $header);
    $col++;
}

// Style the column headers
$headerRange = 'A5:I5';
$sheet->getStyle($headerRange)->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => ['rgb' => '1F2937'],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'F3F4F6'],
    ],
    'borders' => [
        'bottom' => ['borderStyle' => Border::BORDER_THIN],
    ],
]);

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

// Add data rows
$row = 6;
if ($result && $result->num_rows > 0) {
    while ($data = $result->fetch_assoc()) {
        // Map data to specific columns
        $sheet->setCellValue('A' . $row, $data['EmpCode']);
        $sheet->setCellValue('B' . $row, $data['EmpName']);
        $sheet->setCellValue('C' . $row, $data['CompanyName']);
        $sheet->setCellValue('D' . $row, $data['PositionName']);
        $sheet->setCellValue('E' . $row, $data['DepartmentName']);
        $sheet->setCellValue('F' . $row, $data['DivisionName']);
        $sheet->setCellValue('G' . $row, $data['StartDate']);
        $sheet->setCellValue('H' . $row, $data['Status']);
        $sheet->setCellValue('I' . $row, $data['Contact']);
        
        // Style the Status cell
        $statusCell = 'H' . $row;
        if ($data['Status'] == 'Active') {
            $sheet->getStyle($statusCell)->getFont()->getColor()->setRGB('059669');
        } else {
            $sheet->getStyle($statusCell)->getFont()->getColor()->setRGB('DC2626');
        }
        $sheet->getStyle($statusCell)->getFont()->setBold(true);
        
        // Add zebra striping
        if ($row % 2 == 0) {
            $sheet->getStyle('A' . $row . ':I' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('FAFAFA');
        }
        
        $row++;
    }
}

// Auto-size columns
foreach (range('A', 'I') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Add borders to the data range
$dataRange = 'A5:I' . ($row - 1);
$sheet->getStyle($dataRange)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => 'E5E7EB'],
        ],
    ],
]);

// Set up the response headers
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Staff_List_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

// Create Excel file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$con->close();
exit;
