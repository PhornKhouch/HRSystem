<?php
require '../../Config/conect.php';
include 'telegram_helper.php';
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

    // $leavePolicySql = "SELECT  IsOverBalance FROM lmleavetype WHERE Code = ?";
    // $policyStmt = $con->prepare($leavePolicySql);
    // $policyStmt->bind_param("s", $leave['LeaveType']);
    // $policyStmt->execute();
    // $policyResult = $policyStmt->get_result();
    // $leavePolicy = $policyResult->fetch_assoc();
    // if ($leavePolicy['IsOverBalance'] == 0) {
    //     $balanceSql = "SELECT CurrentBalance FROM lmleavebalance WHERE EmpCode = ? AND LeaveType = ? AND inyear = YEAR(CURRENT_DATE)";
    //     $balanceStmt = $con->prepare($balanceSql);
    //     $balanceStmt->bind_param("ss", $empCode, $leaveType);
    //     $balanceStmt->execute();
    //     $balanceResult = $balanceStmt->get_result();
    //     $balance = $balanceResult->fetch_assoc();

    //     if ($balance && $leaveDay > $balance['CurrentBalance']) {
    //         header("Location: ../../view/SSLeaveRequest/create.php?error=" . urlencode("Can not use over leave balance"));
    //         exit;
    //     }
    // }
    // if ($leave['CurrentBalance'] < $leave['LeaveDay']) {
    //     throw new Exception('Can not request over  balance');
    // }

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
    $stmt = $con->prepare("UPDATE lmleavebalance 
        SET 
            Taken = Taken + ?,
            CurrentBalance = CurrentBalance - ?
           
        WHERE EmpCode = ? AND LeaveType = ? AND InYear = YEAR(?)
        AND CurrentBalance >= ?");
    $stmt->bind_param(
        'dssssf',
        $leave['LeaveDay'],
        $leave['LeaveDay'],
        $leave['EmpCode'],
        $leave['LeaveType'],
        $leave['FromDate'],
        $leave['LeaveDay']
    );
    $stmt->execute();

 

    // if ($stmt->affected_rows === 0) {
    //     throw new Exception('Failed to update leave balance');
    // }

    // Commit transaction
    $con->commit();
    //get employee name
    $sql = " SELECT EmpName FROM HRstaffprofile WHERE EmpCode = '$empCode'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $empName = $row['EmpName'];
    } else {
        header("Location: ../../view/SSLeaveRequest/create.php?error=" . urlencode("Employee not found"));
        exit;
    }
    // Send Telegram message
    $empCode = $leave['EmpCode'];
    $leaveType = $leave['LeaveType'];
    $fromDate = $leave['FromDate'];
    $toDate = $leave['ToDate'];
    $message = GetMessageForLeaveApproved($empName, $leaveType, $fromDate, $toDate, "Approved");
    $botToken = "8083716719:AAEwZMyRVg0j2Zf4TXZelPN3TWbRAK2QAvQ";
    $groupID = "-1002586996680";
    sendTelegramMessage($message, $botToken, $groupID);
    // Send success response
    echo json_encode([
        'status' => 'success',
        'message' => 'Leave request approved successfully'
    ]);
} catch (Exception $e) {
    // Rollback on error
    $con->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
