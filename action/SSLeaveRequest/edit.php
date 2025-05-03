<?php
include("../../Config/conect.php");
session_start();

// Get the original leave request to calculate balance adjustment
$id = $_POST['id'];
$empCode = $_POST['empCode'];
$originalQuery = "SELECT LeaveDay, LeaveType FROM lmleaverequest WHERE ID = ? AND EmpCode = ?";
$stmt = $con->prepare($originalQuery);
$stmt->bind_param("is", $id, $empCode);
$stmt->execute();
$result = $stmt->get_result();
$originalRequest = $result->fetch_assoc();

// Validate required fields
if (!isset($_POST['id']) || empty($_POST['id']) ||
    !isset($_POST['empCode']) || empty($_POST['empCode']) ||
    !isset($_POST['leaveType']) || empty($_POST['leaveType']) ||
    !isset($_POST['fromDate']) || empty($_POST['fromDate']) ||
    !isset($_POST['toDate']) || empty($_POST['toDate']) ||
    !isset($_POST['reason']) || empty($_POST['reason']) ||
    !isset($_POST['leaveDay']) || empty($_POST['leaveDay']))  {
    header("Location: ../../view/LeaveRequest/edit.php?id=" . $_POST['id'] . "&error=" . urlencode("All fields are required"));
    exit;
}

// Get form data
$id = $_POST['id'];
$empCode = $_POST['empCode'];
$leaveType = $_POST['leaveType'];
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$reason = $_POST['reason'];
$leaveDay = $_POST['leaveDay'];

// Validate dates
if (strtotime($toDate) < strtotime($fromDate)) {
    header("Location: ../../view/LeaveRequest/edit.php?id=" . $id . "&error=" . urlencode("To Date cannot be earlier than From Date"));
    exit;
}

// Start transaction
$con->begin_transaction();

try {
    // Update leave request
    $sql = "UPDATE lmleaverequest 
            SET LeaveType = ?, FromDate = ?, ToDate = ?, Reason = ?, LeaveDay = ?
            WHERE ID = ? AND EmpCode = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssdis", $leaveType, $fromDate, $toDate, $reason, $leaveDay, $id, $empCode);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update leave request: " . $con->error);
    }

    // Calculate the difference in leave days
    $leaveDayDifference = $leaveDay - $originalRequest['LeaveDay'];//2 -1 
    
    // Update leave balance if there's a change in leave days
    if ($leaveDayDifference != 0) {
        $currentYear = date('Y');
        $updateBalance = "UPDATE lmleavebalance 
                         SET CurrentBalance = CurrentBalance - ?, 
                             Taken = Taken + ?
                         WHERE EmpCode = ? AND LeaveType = ? AND inyear = ?";
        $stmt2 = $con->prepare($updateBalance);
        $stmt2->bind_param("ddssi", $leaveDayDifference, $leaveDayDifference, $empCode, $leaveType, $currentYear);
        
        if (!$stmt2->execute()) {
            throw new Exception("Failed to update leave balance: " . $con->error);
        }
    }

    // Commit transaction
    $con->commit();
    header("Location: ../../view/LeaveRequest/index.php?success=" . urlencode("Leave request updated successfully"));
} catch (Exception $e) {
    // Rollback transaction on error
    $con->rollback();
    header("Location: ../../view/LeaveRequest/edit.php?id=" . $id . "&error=" . urlencode($e->getMessage()));
}

$stmt->close(); 
$stmt2->close();
$con->close();
?>
