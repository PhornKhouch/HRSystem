<?php
include("../../Config/conect.php");

header('Content-Type: application/json');

try {
    $empCode = $_POST['empCode'] ?? '';
    $month = $_POST['month'] ?? '';

    if (empty($empCode) || empty($month)) {
        throw new Exception('Employee code and month are required');
    }    // Check if salary exists and is completed for this employee in this month
    $check_sql = "SELECT ID FROM hisgensalary WHERE EmpCode = ? AND InMonth = ? AND NetSalary > 0";
    $stmt = mysqli_prepare($con, $check_sql);
    
    if (!$stmt) {
        throw new Exception("Database error: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "ss", $empCode, $month);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    echo json_encode([
        'status' => 'success',
        'exists' => mysqli_num_rows($result) > 0
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

mysqli_close($con);
?>
