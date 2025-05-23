<?php
// Disable error reporting to prevent HTML errors in JSON output
error_reporting(0);
ini_set('display_errors', 0);

// Set JSON content type
header('Content-Type: application/json');

// Include database connection
include("../../Config/conect.php");

if (!$con) {
    echo json_encode(array(
        "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => array(),
        "error" => "Database connection failed"
    ));
    exit;
}

// Initialize response array
$response = array();

// DataTables server-side parameters
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// Get filter parameters
$employeeCode = isset($_POST['employeeCode']) ? $_POST['employeeCode'] : '';
$relationshipType = isset($_POST['relationshipType']) ? $_POST['relationshipType'] : 'all';
$gender = isset($_POST['gender']) ? $_POST['gender'] : 'all';
$taxStatus = isset($_POST['taxStatus']) ? $_POST['taxStatus'] : 'all';

// Count total records before filtering
$countQuery = "SELECT COUNT(*) as total FROM hrfamily f inner JOIN hrstaffprofile e ON f.EmpCode = e.EmpCode";
$totalRecords = mysqli_fetch_assoc(mysqli_query($con, $countQuery))['total'];

// Base query
$query = "SELECT 
            f.EmpCode,
           	e.EmpName AS EmpName,
            f.RelationName AS FamilyMemberName,
            f.RelationType,
            f.Gender,
            f.IsTax
          FROM hrfamily f
          inner JOIN hrstaffprofile e ON f.EmpCode = e.EmpCode
          WHERE 1=1
       ";

// Add filters
if (!empty($employeeCode)) {
    $query .= " AND f.EmpCode LIKE '%" . mysqli_real_escape_string($con, $employeeCode) . "%'";
}

if ($relationshipType !== 'all') {
    $query .= " AND f.RelationType = '" . mysqli_real_escape_string($con, $relationshipType) . "'";
}

if ($gender !== 'all') {
    $query .= " AND f.Gender = '" . mysqli_real_escape_string($con, $gender) . "'";
}

if ($taxStatus !== 'all') {
    $query .= " AND f.IsTax = '" . mysqli_real_escape_string($con, $taxStatus) . "'";
}

// Add search functionality
if (!empty($search)) {
    $query .= " AND (f.EmpCode LIKE '%" . mysqli_real_escape_string($con, $search) . "%' OR 
                    e.EmpName LIKE '%" . mysqli_real_escape_string($con, $search) . "%' OR
                    f.RelationName LIKE '%" . mysqli_real_escape_string($con, $search) . "%' OR
                    f.RelationType LIKE '%" . mysqli_real_escape_string($con, $search) . "%' OR
                    f.Gender LIKE '%" . mysqli_real_escape_string($con, $search) . "%')";
}

// Get filtered records count
try {
    $filteredResult = mysqli_query($con, $query);
    if (!$filteredResult) {
        throw new Exception(mysqli_error($con));
    }
    $filteredRecords = mysqli_num_rows($filteredResult);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        "draw" => $draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => array(),
        "error" => $e->getMessage()
    ));
    exit;
}

// Add pagination
$query .= " LIMIT $start, $length";

// Execute final query with pagination
try {
    $result = mysqli_query($con, $query);
    if (!$result) {
        throw new Exception(mysqli_error($con));
    }
    
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            "EmpCode" => $row['EmpCode'],
            "EmpName" => $row['EmpName'],
            "FamilyMemberName" => $row['FamilyMemberName'],
            "RelationType" => $row['RelationType'],
            "Gender" => $row['Gender'],
            "IsTax" => $row['IsTax'] == '1' ? 'Yes' : 'No',
            "Actions" => '<button class="btn btn-sm btn-info view-details" data-empcode="' . $row['EmpCode'] . '"><i class="fas fa-eye"></i></button>'
        );
    }

    echo json_encode(array(
        "draw" => $draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $filteredRecords,
        "data" => $data
    ));

    mysqli_free_result($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        "draw" => $draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => array(),
        "error" => $e->getMessage()
    ));
}

if ($con) {
    mysqli_close($con);
}
exit;