<?php
include("../../Config/conect.php");
session_start();

// Get form data
$empCode = $_POST['empCode'];
$leaveType = $_POST['leaveType'];
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$reason = $_POST['reason'];
$leaveDay = $_POST['leaveDay'];

// Validate dates
if (strtotime($toDate) < strtotime($fromDate)) {
    header("Location: ../../view/LeaveRequest/create.php?error=" . urlencode("To Date cannot be earlier than From Date"));
    exit;
}

// Insert leave request
$sql = "INSERT INTO lmleaverequest (EmpCode, LeaveType, FromDate, ToDate, Reason, LeaveDay, Status) 
        VALUES (?, ?, ?, ?, ?, ?, 'Approved')";
$stmt = $con->prepare($sql);
$stmt->bind_param("sssssd", $empCode, $leaveType, $fromDate, $toDate, $reason, $leaveDay);

// update the lmleavebalance table
$currentYear = date('Y');
$sql2 = "UPDATE lmleavebalance 
         SET CurrentBalance = CurrentBalance - ?, Taken = Taken + ?
         WHERE EmpCode = ? AND LeaveType = ? AND inyear = ?";
$stmt2 = $con->prepare($sql2);
$stmt2->bind_param("ddssi", $leaveDay, $leaveDay, $empCode, $leaveType, $currentYear);

if ($stmt->execute() && $stmt2->execute()) {
    header("Location: ../../view/LeaveRequest/index.php?success=" . urlencode("Leave request created successfully"));
} else {
    header("Location: ../../view/LeaveRequest/create.php?error=" . urlencode("Failed to create leave request: " . $con->error));
}

$stmt->close();
$con->close();
?>
