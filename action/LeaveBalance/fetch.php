<?php
session_start();
include("../../Config/conect.php");

header('Content-Type: application/json');

try {
    // DataTables server-side parameters
    $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
    $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

    // Base query - Updated join condition to use Code instead of id
    $baseQuery = "FROM lmleavebalance lb 
                  LEFT JOIN lmleavetype lt ON lb.LeaveType = lt.Code";

    // Search condition
    $searchCondition = "";
    $params = array();
    $types = "";
    
    if (!empty($search)) {
        $searchCondition = " WHERE lb.EmpCode LIKE ? OR lt.LeaveType LIKE ?";
        $searchParam = "%{$search}%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= "ss";
    }

    // Count total records
    $totalRecordsQuery = "SELECT COUNT(*) as count " . $baseQuery;
    $result = mysqli_query($con, $totalRecordsQuery);
    $totalRecords = mysqli_fetch_assoc($result)['count'];

    // Count filtered records
    $filteredRecordsQuery = "SELECT COUNT(*) as count " . $baseQuery . $searchCondition;
    if (!empty($search)) {
        $stmt = mysqli_prepare($con, $filteredRecordsQuery);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_query($con, $filteredRecordsQuery);
    }
    $filteredRecords = mysqli_fetch_assoc($result)['count'];

    // Ordering
    $order = '';
    if (isset($_POST['order'][0]['column']) && isset($_POST['order'][0]['dir'])) {
        $orderColumn = intval($_POST['order'][0]['column']);
        $orderDir = $_POST['order'][0]['dir'] === 'desc' ? 'DESC' : 'ASC';
        
        $columns = array(
            1 => 'lb.EmpCode',
            2 => 'lt.LeaveType',
            3 => 'lb.Balance',
            4 => 'lb.Entitle',
            5 => 'lb.CurrentBalance',
            6 => 'lb.Taken'
        );
        
        if (isset($columns[$orderColumn])) {
            $order = " ORDER BY " . $columns[$orderColumn] . " " . $orderDir;
        }
    }

    // Main query - Updated to include all necessary fields
    $query = "SELECT lb.id, lb.EmpCode, lt.LeaveType as leave_type, 
              lb.Balance, lb.Entitle, lb.CurrentBalance, lb.Taken 
              " . $baseQuery . $searchCondition . $order . " LIMIT ?, ?";

    // Add limit parameters
    $params[] = $start;
    $params[] = $length;
    $types .= "ii";

    // Execute main query
    $stmt = mysqli_prepare($con, $query);
    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Prepare data
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            'id' => $row['id'],
            'emp_code' => $row['EmpCode'],
            'leave_type' => $row['leave_type'],
            'balance' => $row['Balance'],
            'entitle' => $row['Entitle'],
            'current_balance' => $row['CurrentBalance'],
            'taken' => $row['Taken']
        );
    }

    // Response
    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $data,
        'error' => null
    ]);

} catch (Exception $e) {
    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => 'Error: ' . $e->getMessage()
    ]);
}

mysqli_close($con);
?>
