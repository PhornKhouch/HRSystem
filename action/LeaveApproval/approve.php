<?php
require '../../Config/conect.php';
session_start();

// Get leave request ID
$id = $_POST['id'] ?? null;
$approver = $_SESSION['username'] ?? 'HR Admin'; // Get actual approver from session

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Leave request ID is required']);
    exit;
}

try {
    // Start transaction
    $con->begin_transaction();

    // Get leave request details
    $stmt = $con->prepare("SELECT lr.*, lb.CurrentBalance 
        FROM lmleaverequest lr
        LEFT JOIN lmleavebalance lb ON lr.EmpCode = lb.EmpCode 
            AND lr.LeaveType = lb.LeaveType 
            AND YEAR(lr.FromDate) = lb.InYear
        WHERE lr.ID = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $leave = $result->fetch_assoc();

    if (!$leave) {
        throw new Exception('Leave request not found');
    }

    // Validate current status
    if ($leave['Status'] !== 'Pending') {
        throw new Exception('Leave request has already been processed');
    }

    // Validate leave balance
    if ($leave['CurrentBalance'] < $leave['LeaveDay']) {
        throw new Exception('Can not request over  balance');
    }

    // Update leave request status
    $stmt = $con->prepare("UPDATE lmleaverequest 
        SET Status = 'Approved',
            ApprovedBy = ?,
            UpdatedAt = NOW()
        WHERE ID = ? AND Status = 'Pending'");
    $stmt->bind_param('si', $approver, $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception('Failed to update leave request status');
    }

    // Update leave balance
    // $stmt = $con->prepare("UPDATE lmleavebalance 
    //     SET 
    //         Taken = Taken + ?,
    //         CurrentBalance = CurrentBalance - ?
           
    //     WHERE EmpCode = ? AND LeaveType = ? AND InYear = YEAR(?)
    //     AND CurrentBalance >= ?");
    // $stmt->bind_param(
    //     'dssssf',
    //     $leave['LeaveDay'],
    //     $leave['EmpCode'],
    //     $leave['LeaveType'],
    //     $leave['FromDate'],
    //     $leave['LeaveDay']
    // );
    // $stmt->execute();

 

    // if ($stmt->affected_rows === 0) {
    //     throw new Exception('Failed to update leave balance');
    // }

    // Commit transaction
    $con->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Leave request approved successfully'
    ]);
} catch (Exception $e) {
    // Rollback on error
    $con->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
