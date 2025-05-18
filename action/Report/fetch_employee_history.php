<?php
session_start();
include("../../Config/conect.php");

header('Content-Type: application/json');

if (!isset($_POST['empCode'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Employee code is required"
    ]);
    exit;
}

$empCode = $_POST['empCode'];
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : null;
$department = isset($_POST['department']) ? $_POST['department'] : null;
$status = isset($_POST['status']) && $_POST['status'] !== 'All' ? $_POST['status'] : null;

// Get employee details
$empSql = "SELECT EmpCode, EmpName FROM hrstaffprofile WHERE EmpCode = ?";
$empStmt = $con->prepare($empSql);
$empStmt->bind_param("s", $empCode);
$empStmt->execute();
$empResult = $empStmt->get_result();
$employee = $empResult->fetch_assoc();

if (!$employee) {
    echo json_encode([
        "status" => "error",
        "message" => "Employee not found"
    ]);
    exit;
}

// Get career history
    $historySql = "SELECT 
    DATE_FORMAT(h.EndDate, '%Y-%m-%d') as EffectiveDate,
    d.Description as Department,
    p.Description as Position,
    h.CareerHistoryType as Status,
    COALESCE(h.Remark, '-') as Remarks
FROM careerhistory h
LEFT JOIN hrdepartment d ON h.Department = d.Code
LEFT JOIN hrposition p ON h.PositionTitle = p.Code
WHERE h.EmployeeID = ?
    " . ($startDate ? " AND h.EndDate >= ? " : "") 
    . ($endDate ? " AND h.EndDate <= ? " : "")
    . ($department && $department !== 'all' ? " AND h.Department = ? " : "")
    . ($status && $status !== 'all' ? " AND h.CareerHistoryType = ? " : "") . "
ORDER BY h.EndDate DESC";

$historyStmt = $con->prepare($historySql);

// Build parameter types and values array
$types = "s";
$params = [$empCode];

if ($startDate) {
    $types .= "s";
    $params[] = $startDate;
}
if ($endDate) {
    $types .= "s";
    $params[] = $endDate;
}
if ($department && $department !== 'all') {
    $types .= "s";
    $params[] = $department;
}
if ($status && $status !== 'all') {
    $types .= "s";
    $params[] = $status;
}

$historyStmt->bind_param($types, ...$params);
$historyStmt->execute();
$historyResult = $historyStmt->get_result();

$history = [];
while ($row = $historyResult->fetch_assoc()) {
    $history[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => [
        "employee" => $employee,
        "history" => $history
    ]
]);

$empStmt->close();
$historyStmt->close();
$con->close();
