<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

include("../../Config/conect.php");

if (!$con) {
    echo json_encode(array("error" => "Database connection failed"));
    exit;
}

// Get parameters
$empCode = isset($_POST['empCode']) ? $_POST['empCode'] : '';
$month = isset($_POST['month']) ? $_POST['month'] : '';

if (empty($empCode) || empty($month)) {
    echo json_encode(array("error" => "Employee code and month are required"));
    exit;
}

// Query to get employee salary details
$query = "SELECT 
            s.EmpCode,
            e.EmpName AS EmployeeName,
            d.Description AS DepartmentName,
            p.Description AS PositionName,
            c.Description AS CompanyName,
            10 AS CompanyLogo,
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

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        echo json_encode(array("error" => "No salary record found for the selected month"));
    }

    mysqli_stmt_close($stmt);
} catch (Exception $e) {
    echo json_encode(array("error" => $e->getMessage()));
} finally {
    if ($con) {
        mysqli_close($con);
    }
}
