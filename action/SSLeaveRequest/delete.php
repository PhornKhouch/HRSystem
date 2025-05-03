<?php
include("../../Config/conect.php");
session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../../view/SSLeaveRequest/index.php?error=" . urlencode("Invalid request"));
    exit;
}

$id = $_GET['id'];

// Check if leave request exists and is in pending status
$checkSql = "SELECT Status, LeaveDay FROM lmleaverequest WHERE ID = ?";
$checkStmt = $con->prepare($checkSql);
$checkStmt->bind_param("i", $id);
$checkStmt->execute();
$result = $checkStmt->get_result();
$leaveRequest = $result->fetch_assoc();

if (!$leaveRequest) {
    header("Location: ../../view/SSLeaveRequest/index.php?error=" . urlencode("Leave request not found"));
    exit;
}

if ($leaveRequest['Status'] !== 'Pending') {
    header("Location: ../../view/SSLeaveRequest/index.php?error=" . urlencode("Only pending leave requests can be deleted"));
    exit;
}

// Delete the leave request
$sql = "DELETE FROM lmleaverequest WHERE ID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);

// Delete the leave balance
$sql2 = "UPDATE lmleavebalance SET CurrentBalance = CurrentBalance + ?, Taken = Taken - ? WHERE ID = ?";
$stmt2 = $con->prepare($sql2);
$stmt2->bind_param("ddi", $leaveRequest['LeaveDay'], $leaveRequest['LeaveDay'], $id);

if ($stmt->execute() && $stmt2->execute()) {
    header("Location: ../../view/SSLeaveRequest/index.php?success=" . urlencode("Leave request deleted successfully"));
} else {
    header("Location: ../../view/SSLeaveRequest/index.php?error=" . urlencode("Failed to delete leave request"));
}

$checkStmt->close();
$stmt->close();
$stmt2->close();
$con->close();
?>
