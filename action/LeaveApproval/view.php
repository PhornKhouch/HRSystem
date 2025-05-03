<?php
require '../../Config/conect.php';

// Get leave request ID
$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Leave request ID is required']);
    exit;
}

try {
    // Get leave request details with employee name
    $stmt = $con->prepare("
        SELECT 
            lr.*,
            sp.EmpName as emp_name
        FROM lmleaverequest lr
        LEFT JOIN hrstaffprofile sp ON lr.EmpCode = sp.EmpCode
        WHERE lr.ID = ?
    ");
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $con->error);
    }
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to execute query: ' . $stmt->error);
    }
    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception('Failed to retrieve result: ' . $con->error);
    }
    $leave = $result->fetch_assoc();

    if (!$leave) {
        throw new Exception('Leave request not found');
    }

    // Format dates
    $leave['from_date'] = date('d M Y', strtotime($leave['FromDate']));
    $leave['to_date'] = date('d M Y', strtotime($leave['ToDate']));
    $leave['no_days'] = number_format($leave['LeaveDay'], 1);

    echo json_encode([
        'status' => 'success',
        'data' => [
            'emp_name' => $leave['emp_name'],
            'leave_type' => $leave['LeaveType'],
            'from_date' => $leave['from_date'],
            'to_date' => $leave['to_date'],
            'no_days' => $leave['no_days'],
            'reason' => $leave['Reason']
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
