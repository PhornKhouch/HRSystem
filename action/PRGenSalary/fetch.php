<?php
header('Content-Type: application/json');

try {
    include("../../Config/conect.php");

    if (!$con) {
        throw new Exception("Database connection failed");
    }

    // Get month parameter (default to current month if not provided)
    $month = $_POST['month'] ?? date('Y-m');

    // Prepare the base query
    $sql = "SELECT 
       s.EmpCode,
       s.EmpName,
       s.StartDate,
       D.Description AS Department,
       P.Description AS Position,
       DI.Description AS Division,
       COM.Description as Company
    FROM hrstaffprofile s
    INNER JOIN hrdepartment D ON s.Department = D.Code
    INNER JOIN hrposition P ON s.Position = P.Code
    INNER JOIN hrdivision DI ON DI.Code = s.Division
    INNER JOIN hrcompany COM ON COM.Code = s.Company
    WHERE s.Status = 'Active'
    ORDER BY s.EmpCode";

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . mysqli_error($con));
    }

    // No parameters to bind for this query
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Failed to execute query: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception("Failed to get result set: " . mysqli_error($con));
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $data
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'error' => $e->getMessage(),
        'data' => []
    ]);
} finally {
    if (isset($stmt)) mysqli_stmt_close($stmt);
    if (isset($con)) mysqli_close($con);
}
?>
