<?php
require '../../vendor/autoload.php';
require '../../Config/conect.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()
    ->setCreator('CLUBCODE-HR')
    ->setLastModifiedBy('CLUBCODE-HR')
    ->setTitle('Career History List')
    ->setSubject('Career History List')
    ->setDescription('Career History List generated from HR System')
    ->setKeywords('career history hr system')
    ->setCategory('HR Reports');

// Get the active sheet
$sheet = $spreadsheet->getActiveSheet();

// Merge cells for header
$sheet->mergeCells('A1:I1');
$sheet->mergeCells('A2:I2');

$sheet->setCellValue('A1', 'CLUBCODE-HR');
$sheet->setCellValue('A2', 'Career History List');

// Style the header
$headerStyle = [
    'font' => [
        'bold' => true,
        'size' => 16,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];
$sheet->getStyle('A1:I2')->applyFromArray($headerStyle);
$sheet->getRowDimension(1)->setRowHeight(30);
$sheet->getRowDimension(2)->setRowHeight(25);

// Set up the table headers
$headers = ['Career', 'ID', 'Name', 'Position', 'Department', 'Effective Date', 'Resignation Date', 'Remark', 'Increase'];
$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

// Style for table headers
$tableHeaderStyle = [
    'font' => [
        'bold' => true,
        'color' => [
            'rgb' => '1F2937'
        ]
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'F3F4F6'
        ]
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => [
                'rgb' => 'D1D5DB'
            ]
        ]
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

// Add headers
foreach ($headers as $key => $header) {
    $sheet->setCellValue($columns[$key] . '4', $header);
}
$sheet->getStyle('A4:I4')->applyFromArray($tableHeaderStyle);

// Fetch data
$sql = "SELECT ch.*, sp.EmpName 
        FROM careerhistory ch 
        LEFT JOIN hrstaffprofile sp ON ch.EmployeeID = sp.EmpCode 
        ORDER BY ch.CreatedAt DESC";
$result = $con->query($sql);

// Add data
$row = 5;
if ($result && $result->num_rows > 0) {
    while ($data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $data['CareerHistoryType']);
        $sheet->setCellValue('B' . $row, $data['EmployeeID']);
        $sheet->setCellValue('C' . $row, $data['EmpName']);
        $sheet->setCellValue('D' . $row, $data['PositionTitle']);
        $sheet->setCellValue('E' . $row, $data['Department']);
        $sheet->setCellValue('F' . $row, date('d M Y', strtotime($data['StartDate'])));
        $sheet->setCellValue('G' . $row, $data['EndDate'] ? date('d M Y', strtotime($data['EndDate'])) : '-');
        $sheet->setCellValue('H' . $row, $data['Remark'] ?? '-');
        $sheet->setCellValue('I' . $row, $data['Increase'] ? number_format($data['Increase'], 2) : '-');
        
        // Add zebra striping
        if ($row % 2 == 0) {
            $sheet->getStyle('A' . $row . ':I' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('FAFAFA');
        }
        
        $row++;
    }

    // Set borders for the data
    $sheet->getStyle('A4:I' . ($row - 1))->getBorders()->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN)
        ->getColor()
        ->setRGB('D1D5DB');

    // Auto-size columns
    foreach (range('A', 'I') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
} else {
    $sheet->setCellValue('A5', 'No data found');
    $sheet->mergeCells('A5:I5');
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// Create writer and output file
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Career_History_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');

$writer->save('php://output');
exit;
