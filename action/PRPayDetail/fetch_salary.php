<?php
    //fetch_salary.php
    error_reporting(0); // Disable error reporting
    ini_set('display_errors', 0); // Don't display errors
    header('Content-Type: application/json'); // Set content type to JSON
    
    include("../../Config/conect.php");
    
    if (!isset($_POST['empCode']) || !isset($_POST['month'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required parameters'
        ]);
        exit;
    }
try 
{
    $empCode = $_POST['empCode'];
    $month = $_POST['month'];    // First get employee details
    $empQuery = "SELECT EmpCode, EmpName, Department, Position FROM hrstaffprofile WHERE EmpCode = '$empCode'";
    $empResult = mysqli_query($con, $empQuery);
    if (!$empResult) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch employee details: ' . mysqli_error($con)
        ]);
        exit;
    }
    $empData = mysqli_fetch_assoc($empResult);
    if (!$empData) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Employee not found'
        ]);
        exit;
    }

    // Then get salary details
    $Hisgen = "SELECT * FROM hisgensalary WHERE EmpCode = '$empCode' AND InMonth = '$month'";
    $result = mysqli_query($con, $Hisgen);
    if (!$result) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch salary details: ' . mysqli_error($con)
        ]);
        exit;
    }
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No salary data found for the selected month'
        ]);
        exit;
    }

    // Combine employee and salary details
    $details = array_merge($empData, [
        'InMonth' => $row['InMonth'],
        'Salary' => floatval($row['Salary']),
        'Allowance' => floatval($row['Allowance']),
        'OT' => floatval($row['OT']),
        'Bonus' => floatval($row['Bonus']),
        'Deduction' => floatval($row['Dedection']),
        'LeavedTax' => floatval($row['LeavedTax']),
        'Amtobetax' => floatval($row['Amtobetax']),
        'Grosspay' => floatval($row['Grosspay']),
        'Family' => floatval($row['Family']),
        'UntaxAm' => floatval($row['UntaxAm']),
        'NSSF' => floatval($row['NSSF']),
        'NetSalary' => floatval($row['NetSalary'])
    ]);
    echo json_encode([
        'status' => 'success',
        'details' => $details
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
    mysqli_close($con); 
?>