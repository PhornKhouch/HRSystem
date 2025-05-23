<?php
error_reporting(0);
header('Content-Type: application/json');

require_once("../../Config/conect.php");

if (!$con) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$inmonth = isset($_POST['inmonth']) ? $_POST['inmonth'] : date('Y-m');
$department = isset($_POST['department']) ? $_POST['department'] : 'all';

try {
    $where = "WHERE InMonth = ?";
    $params = [$inmonth];

    if ($department !== 'all') {
        $where .= " AND hrstaffprofile.Department = ?";
        $params[] = $department;
    }

    $sql = "SELECT 
                d.Code as DepartmentCode,
                d.Description as DepartmentName,
                COUNT(DISTINCT hrstaffprofile.EmpCode) as EmployeeCount,
                SUM(h.Salary) as TotalSalary,
                SUM(h.Allowance) as TotalAllowance,
                SUM(h.OT) as TotalOT,
                SUM(h.Bonus) as TotalBonus,
                SUM(h.Dedction) as TotalDeduction,
                SUM(h.LeavedTax) as TotalLeavedTax,
                SUM(h.Amtobetax) as TotalAmtobetax,
                SUM(h.Grosspay) as TotalGrossPay,
                SUM(h.Family) as TotalFamily,
                SUM(h.UntaxAm) as TotalUntaxedAmount,
                SUM(h.NSSF) as TotalNSSF,
                SUM(h.NetSalary) as TotalNetSalary,
                AVG(h.NetSalary) as AverageSalary
            FROM hisgensalary h
            Left join hrstaffprofile On h.EmpCode = hrstaffprofile.EmpCode
            LEFT JOIN hrdepartment d ON hrstaffprofile.Department = d.Code
            $where
            GROUP BY d.Code, d.Description
            ORDER BY d.Description";

    if (!($stmt = mysqli_prepare($con, $sql))) {
        throw new Exception('Prepare failed: ' . mysqli_error($con));
    }
    
    if (!mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params)) {
        throw new Exception('Binding parameters failed: ' . mysqli_stmt_error($stmt));
    }
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
    }
    
    if (!($result = mysqli_stmt_get_result($stmt))) {
        throw new Exception('Getting result failed: ' . mysqli_stmt_error($stmt));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Convert all numeric values to floats for consistent JSON encoding
        foreach ($row as $key => $value) {
            if (is_numeric($value)) {
                $row[$key] = floatval($value);
            }
        }
        $data[] = $row;
    }
    
    if (mysqli_error($con)) {
        throw new Exception('Error fetching data: ' . mysqli_error($con));
    }

    echo json_encode([
        'data' => $data,
        'success' => true
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching monthly summary: ' . $e->getMessage()
    ]);
}
?>
