<?php
include("../../Config/conect.php");

header('Content-Type: application/json');

try {
    if (!$con) {
        throw new Exception("Database connection failed");
    }

    // Validate input parameters
    if (!isset($_POST['inmonth']) || empty($_POST['inmonth'])) {
        throw new Exception("Month parameter is required");
    }

    $inmonth = $_POST['inmonth'];
    $department = isset($_POST['department']) ? $_POST['department'] : 'all';

    // Log request for debugging
    error_log("Monthly Pay Report Request - Month: " . $inmonth . ", Department: " . $department);

    // Validate month format (YYYY-MM)
    if (!preg_match('/^\d{4}-\d{2}$/', $inmonth)) {
        throw new Exception("Invalid month format. Use YYYY-MM");
    }

    // Build base query with joins for employee info
    $sql = "SELECT 
                h.EmpCode,
                h.InMonth,
                COALESCE(h.Salary, 0) as Salary,
                COALESCE(h.Allowance, 0) as Allowance,
                COALESCE(h.Bonus, 0) as Bonus,
                COALESCE(h.Dedction, 0) as Dedction,
                COALESCE(h.Grosspay, 0) as Grosspay,
                COALESCE(h.UntaxAm, 0) as UntaxAm,
                COALESCE(h.NSSF, 0) as NSSF,
                COALESCE(h.NetSalary, 0) as NetSalary,
                p.EmpName as EmployeeName,
                d.Description as DepartmentName
            FROM hisgensalary h
            LEFT JOIN hrstaffprofile p ON h.EmpCode = p.EmpCode
            LEFT JOIN hrdepartment d ON p.Department = d.Code ";

    // Add WHERE clause
    $params = [];
    $types = '';
    
    $sql .= " WHERE h.InMonth = ?";
    $params[] = $inmonth;
    $types .= 's';

    if ($department !== 'all') {
        $sql .= " AND p.Department = ?";
        $params[] = $department;
        $types .= 's';
    }

    // Order by employee code
    $sql .= " ORDER BY h.EmpCode ASC";

    // Log query for debugging
    error_log("SQL Query: " . $sql);
    error_log("Parameters: " . json_encode($params));

    // Prepare and execute
    $stmt = $con->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Failed to prepare statement: " . $con->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute query: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $data = [];
    
    while ($row = $result->fetch_assoc()) {
        // Convert numeric strings to floats for proper JSON encoding
        $row['Salary'] = floatval($row['Salary']);
        $row['Allowance'] = floatval($row['Allowance']);
        $row['Bonus'] = floatval($row['Bonus']);
        $row['Dedction'] = floatval($row['Dedction']);
        $row['Grosspay'] = floatval($row['Grosspay']);
        $row['UntaxAm'] = floatval($row['UntaxAm']);
        $row['NSSF'] = floatval($row['NSSF']);
        $row['NetSalary'] = floatval($row['NetSalary']);
        $data[] = $row;
    }

    echo json_encode([
        "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
        "recordsTotal" => count($data),
        "recordsFiltered" => count($data),
        "data" => $data
    ]);

    $stmt->close();

} catch (Exception $e) {
    error_log("Error in Monthly Pay Report: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}