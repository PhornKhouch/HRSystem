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
    $countSql = "SELECT COUNT(*) as total FROM (
        SELECT A.InMonth
        FROM hisgensalary S
        INNER JOIN prapprovesalary A ON S.InMonth = A.InMonth
        WHERE YEAR(STR_TO_DATE(A.InMonth, '%Y-%m')) = ?";
    $params = [$year];
    $types = "i";

    // Add status condition if not ALL
    if ($status !== '' && $status !== 'ALL') {
        $countSql .= " AND A.status = ?";
        $params[] = $status;
        $types .= "s";
    }

    $countSql .= " GROUP BY A.InMonth) as t";

    // Add search condition if search value exists
    if (!empty($search)) {
        $countSql = str_replace("WHERE", "WHERE A.InMonth LIKE ? AND", $countSql);
        $searchParam = "%$search%";
        array_splice($params, 0, 0, [$searchParam]);
        $types = "s" . $types;
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
        sum(s.Salary) as TotalSalary,
        sum(s.Allowance) as TotalAllowance,
        sum(s.OT) as TotalOT,
        sum(s.Bonus) as TotalBonus,
        sum(s.Dedction) as TotalDed,
        sum(s.Grosspay) as TotalGross,
        Sum(s.NetSalary) as NetSalary,
        A.InMonth,
        A.status,
        A.Remark,
        A.ID
    FROM hisgensalary S
    INNER JOIN prapprovesalary A ON S.InMonth = A.InMonth
    GROUP BY A.InMonth, A.status, A.Remark, A.ID";

    $params = [$year];
    $types = "i";

    // Add status condition if not ALL
    if ($status !== '' && $status !== 'ALL') {
        $sql .= " AND A.status = ?";
        $params[] = $status;
        $types .= "s";
    }

    // Add search condition if search value exists
    if (!empty($search)) {
        $sql = str_replace("WHERE", "WHERE A.InMonth LIKE ? AND", $sql);
        $searchParam = "%$search%";
        array_splice($params, 0, 0, [$searchParam]);
        $types = "s" . $types;
    }

    // Add sorting
    $orderColumn = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 6;
    $orderDir = isset($_POST['order'][0]['dir']) ? strtoupper($_POST['order'][0]['dir']) : 'DESC';
    $orderDir = in_array($orderDir, ['ASC', 'DESC']) ? $orderDir : 'DESC';

    $columns = [
        'A.InMonth',                    // 0: Month
        'sum(s.Salary)',                // 1: Total Salary
        'sum(s.Allowance)',             // 2: Total Allowance
        'sum(s.OT)',                    // 3: Total OT
        'sum(s.Bonus)',                 // 4: Total Bonus
        'sum(s.Dedction)',              // 5: Total Deduction
        'sum(s.Grosspay)',              // 6: Total Gross
        'Sum(s.NetSalary)',             // 7: Net Salary
        'A.status',                     // 8: Status
        'A.Remark',                     // 9: Remark
        null                           // 10: Actions (not sortable)
    ];

    if (isset($columns[$orderColumn]) && $columns[$orderColumn] !== null) {
        $sql .= " ORDER BY " . $columns[$orderColumn] . " " . $orderDir;
    } else {
        $sql .= " ORDER BY A.InMonth DESC";
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
            'in_month' => htmlspecialchars($row['InMonth']),
            'total_salary' => number_format((float)$row['TotalSalary'], 2),
            'total_allowance' => number_format((float)$row['TotalAllowance'], 2),
            'total_ot' => number_format((float)$row['TotalOT'], 2),
            'total_bonus' => number_format((float)$row['TotalBonus'], 2),
            'total_deduction' => number_format((float)$row['TotalDed'], 2),
            'total_gross' => number_format((float)$row['TotalGross'], 2),
            'net_salary' => number_format((float)$row['NetSalary'], 2),
            'status' => htmlspecialchars($row['status']),
            'remark' => htmlspecialchars($row['Remark'])
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
