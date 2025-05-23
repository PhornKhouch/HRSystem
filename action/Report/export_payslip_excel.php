<?php
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="PaySlip.xls"');
header('Cache-Control: max-age=0');

include("../../../Config/conect.php");

$empCode = $_GET['empCode'];
$month = $_GET['month'];

if (!$empCode || !$month) {
    die("Missing required parameters");
}

// Query to get employee salary details
$query = "SELECT 
            s.EmpCode,
            e.EmpName AS EmployeeName,
            d.Description AS DepartmentName,
            p.Description AS PositionName,
            c.Description AS CompanyName,
            s.InMonth,
            s.Salary,
            s.Allowance,
            s.OT,
            s.Bonus,
            s.Dedction,
            s.Grosspay,
            s.UntaxAm,
            s.NSSF,
            s.NetSalary
          FROM hisgensalary s
          INNER JOIN hrstaffprofile e ON s.EmpCode = e.EmpCode
          INNER JOIN hrdepartment d ON e.Department = d.Code
          INNER JOIN hrposition p ON e.Position = p.Code
          INNER JOIN hrcompany c ON e.Company = c.Code
          WHERE s.EmpCode = ? AND s.InMonth = ?";

try {
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $empCode, $month);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("No data found");
    }

    // Format the date
    $monthYear = date('F Y', strtotime($month));

    // Calculate totals
    $totalEarnings = $row['Salary'] + $row['Allowance'] + $row['Bonus'];
    $totalDeductions = $row['NSSF'] + $row['Dedction'];
    $netPay = $totalEarnings - $totalDeductions;

    // Generate Excel content
    $output = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'>";
    $output .= "<head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
    $output .= "<style>.header{background:#f0f0f0;font-weight:bold;text-align:center}.section-header{background:#e0e0e0;font-weight:bold}.total-row{font-weight:bold}.amount{text-align:right}</style>";
    $output .= "</head><body>";

    // Company header
    $output .= "<table border='1' cellspacing='0' cellpadding='5' width='100%'>";
    $output .= "<tr><th colspan='2' class='header' style='font-size:14pt'>" . htmlspecialchars($row['CompanyName']) . "</th></tr>";
    $output .= "<tr><th colspan='2' class='header'>PAYSLIP</th></tr>";
    $output .= "<tr><th colspan='2' class='header'>" . htmlspecialchars($monthYear) . "</th></tr>";
    
    // Employee Information
    $output .= "<tr><td width='30%'>Employee Code</td><td>" . htmlspecialchars($row['EmpCode']) . "</td></tr>";
    $output .= "<tr><td>Employee Name</td><td>" . htmlspecialchars($row['EmployeeName']) . "</td></tr>";
    $output .= "<tr><td>Department</td><td>" . htmlspecialchars($row['DepartmentName']) . "</td></tr>";
    $output .= "<tr><td>Position</td><td>" . htmlspecialchars($row['PositionName']) . "</td></tr>";
    
    // Earnings Section
    $output .= "<tr><th colspan='2' class='section-header'>Earnings</th></tr>";
    $output .= "<tr><td>Basic Salary</td><td class='amount'>" . number_format($row['Salary'], 2) . "</td></tr>";
    $output .= "<tr><td>Allowance</td><td class='amount'>" . number_format($row['Allowance'], 2) . "</td></tr>";
    $output .= "<tr><td>Bonus</td><td class='amount'>" . number_format($row['Bonus'], 2) . "</td></tr>";
    $output .= "<tr class='total-row'><td>Total Earnings</td><td class='amount'>" . number_format($totalEarnings, 2) . "</td></tr>";
    
    // Deductions Section
    $output .= "<tr><th colspan='2' class='section-header'>Deductions</th></tr>";
    $output .= "<tr><td>NSSF</td><td class='amount'>" . number_format($row['NSSF'], 2) . "</td></tr>";
    $output .= "<tr><td>Other Deductions</td><td class='amount'>" . number_format($row['Dedction'], 2) . "</td></tr>";
    $output .= "<tr class='total-row'><td>Total Deductions</td><td class='amount'>" . number_format($totalDeductions, 2) . "</td></tr>";
    
    // Net Pay
    $output .= "<tr class='total-row'><th>Net Pay</th><td class='amount'>" . number_format($netPay, 2) . "</td></tr>";
    $output .= "</table></body></html>";
    
    echo $output;

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
} finally {
    if ($con) {
        mysqli_close($con);
    }
}
