<?php
session_start();
include("../../Config/conect.php");

header('Content-Type: application/json');

$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$status = $_POST['status'];
$department = $_POST['department'];

// Build the base query
$sql = "SELECT 
            h.EmployeeID,
            s.EmpName,
            d.Description as Department,
            p.Description as Position,
            h.CareerHistoryType as Status,
            h.EndDate as Date
        FROM careerhistory h
        LEFT JOIN hrstaffprofile s ON h.EmployeeID = s.EmpCode
        LEFT JOIN hrdepartment d ON h.Department = d.Code
        LEFT JOIN hrposition p ON h.PositionTitle = p.Code
        WHERE (h.EndDate BETWEEN ? AND ? OR h.StartDate BETWEEN ? AND ? OR (h.EndDate IS NULL AND h.CareerHistoryType = 'NEW')) " ;

$params = [$startDate, $endDate, $startDate, $endDate];
$types = "ssss";

// Add status filter
if ($status !== 'all') {
    $sql .= " AND h.CareerHistoryType = ?";
    $params[] = $status;
    $types .= "s";
}

// Add department filter
if ($department !== 'all') {
    $sql .= " AND h.Department = ?";
    $params[] = $department;
    $types .= "s";
}

// Add sorting
$sql .= " ORDER BY h.EndDate DESC";

// Prepare and execute the query
$stmt = $con->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    // Format the status
    $row['Status'] = ucfirst(strtolower($row['Status']));
    $data[] = $row;
}

// Count total records without filtering
$countSql = "SELECT COUNT(*) as total FROM careerhistory";
$totalRecords = $con->query($countSql)->fetch_assoc()['total'];

// Count filtered records
$filteredRecords = count($data);

echo json_encode([
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($filteredRecords),
    "data" => $data
]);

$stmt->close();
$con->close();
