<?php
header('Content-Type: application/json');

include("../../../Config/conect.php");

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
$filteredQuery = "SELECT COUNT(*) as total FROM (" . $query . ") as filtered";
try {
    $filteredResult = mysqli_query($con, $filteredQuery);
    if (!$filteredResult) {
        throw new Exception("Failed to count filtered records: " . mysqli_error($con));
    }
    $filteredRecords = mysqli_fetch_assoc($filteredResult)['total'];
    mysqli_free_result($filteredResult);
} catch (Exception $e) {
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
session_start();
// Execute query
try {
    $result = mysqli_query($con, $query);
    $_SESSION['data']=$result;
    echo $result;
    //header("Location: ../../../View/Report/EmployeeFamily/index.php");
} catch (Exception $e) {
    echo json_encode(array(
        "draw" => $draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => array(),
        "error" => "Exception occurred: " . $e->getMessage()
    ));
}

// End script
exit;