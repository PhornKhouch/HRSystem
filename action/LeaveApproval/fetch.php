<?php
// Prevent any unwanted output
ob_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require '../../Config/conect.php';

    // Get DataTables parameters
    $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
    $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

    // Get filters
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');

    // Base query for total count
    $countSql = "SELECT COUNT(*) as total FROM lmleaverequest lr LEFT JOIN hrstaffprofile sp ON lr.EmpCode = sp.EmpCode WHERE YEAR(lr.FromDate) = ?";
    $params = [$year];
    $types = "i";

    // Add status condition if not ALL
    if ($status !== '' && $status !== 'ALL') {
        $countSql .= " AND lr.Status = ?";
        $params[] = $status;
        $types .= "s";
    }

    // Add search condition if search value exists
    if (!empty($search)) {
        $countSql .= " AND (
            lr.EmpCode LIKE ? 
            OR lr.LeaveType LIKE ?
            OR sp.EmpName LIKE ?
            OR sp.Department LIKE ?
            OR sp.Position LIKE ?
            OR sp.Level LIKE ?
        )";
        $searchParam = "%$search%";
        $params = array_merge($params, array_fill(0, 6, $searchParam));
        $types .= "ssssss";
    }

    // Get total records
    $stmt = $con->prepare($countSql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $con->error);
    }
    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $countResult = $stmt->get_result();
    $totalRecords = $countResult->fetch_assoc()['total'];
    $totalFiltered = $totalRecords;

    // Main query
    $sql = "SELECT 
        lr.ID,
        lr.EmpCode,
        lr.LeaveType,
        lr.FromDate,
        lr.ToDate,
        lr.LeaveDay as NumberOfDays,
        lr.Reason,
        lr.Status,
        sp.EmpName,
        sp.Department,
        sp.Position,
        sp.Level
    FROM lmleaverequest lr
    LEFT JOIN hrstaffprofile sp ON lr.EmpCode = sp.EmpCode
    WHERE YEAR(lr.FromDate) = ?";

    $params = [$year];
    $types = "i";

    // Add status condition if not ALL
    if ($status !== '' && $status !== 'ALL') {
        $sql .= " AND lr.Status = ?";
        $params[] = $status;
        $types .= "s";
    }

    // Add search condition if search value exists
    if (!empty($search)) {
        $sql .= " AND (
            lr.EmpCode LIKE ? 
            OR lr.LeaveType LIKE ?
            OR sp.EmpName LIKE ?
            OR sp.Department LIKE ?
            OR sp.Position LIKE ?
            OR sp.Level LIKE ?
        )";
        $searchParam = "%$search%";
        $params = array_merge($params, array_fill(0, 6, $searchParam));
        $types .= "ssssss";
    }

    // Add sorting
    $orderColumn = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 6;
    $orderDir = isset($_POST['order'][0]['dir']) ? strtoupper($_POST['order'][0]['dir']) : 'DESC';
    $orderDir = in_array($orderDir, ['ASC', 'DESC']) ? $orderDir : 'DESC';

    $columns = [
        'lr.EmpCode',        // 0: Employee Code
        'sp.EmpName',        // 1: Employee Name
        'sp.Department',     // 2: Department
        'sp.Position',       // 3: Position
        'sp.Level',          // 4: Level
        null,               // 5: Units (not sortable)
        'lr.FromDate',       // 6: From Date
        'lr.ToDate',         // 7: To Date
        'lr.LeaveType',      // 8: Leave Type
        'lr.LeaveDay',       // 9: No. Days
        null                // 10: Actions (not sortable)
    ];

    if (isset($columns[$orderColumn]) && $columns[$orderColumn] !== null) {
        $sql .= " ORDER BY " . $columns[$orderColumn] . " " . $orderDir;
    } else {
        $sql .= " ORDER BY lr.FromDate DESC";
    }

    // Add limit
    $sql .= " LIMIT ?, ?";
    $params[] = $start;
    $params[] = $length;
    $types .= "ii";

    // Execute main query
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $con->error);
    }
    $stmt->bind_param($types, ...$params);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $result = $stmt->get_result();

    // Prepare data
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['ID'],
            'emp_code' => htmlspecialchars($row['EmpCode']),
            'emp_name' => htmlspecialchars($row['EmpName']),
            'department' => htmlspecialchars($row['Department']),
            'position' => htmlspecialchars($row['Position']),
            'level' => htmlspecialchars($row['Level']),
            'units' => 'Day',
            'from_date' => date('d M Y', strtotime($row['FromDate'])),
            'to_date' => date('d M Y', strtotime($row['ToDate'])),
            'leave_type' => htmlspecialchars($row['LeaveType']),
            'no_days' => number_format((float)$row['NumberOfDays'], 1),
            'reason' => htmlspecialchars($row['Reason']),
            'status' => htmlspecialchars($row['Status'])
        ];
    }

    // Clear any previous output
    ob_clean();

    // Set headers
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-cache, no-store, must-revalidate');

    // Output JSON
    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => (int)$totalRecords,
        'recordsFiltered' => (int)$totalFiltered,
        'data' => $data,
        'debug' => [
            'status' => $status,
            'year' => $year,
            'search' => $search,
            'sql' => $sql
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Clear any previous output
    ob_clean();
    
    // Set headers
    header('Content-Type: application/json; charset=utf-8');
    header('HTTP/1.1 500 Internal Server Error');
    
    // Output error JSON
    echo json_encode([
        'draw' => $draw ?? 1,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => $e->getMessage(),
        'debug' => [
            'status' => $status ?? null,
            'year' => $year ?? null,
            'search' => $search ?? null,
            'sql' => $sql ?? null
        ]
    ]);
}

// End output buffering
ob_end_flush();
