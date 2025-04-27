<?php
include("../../Config/conect.php");
session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../../view/LeaveRequest/index.php?error=" . urlencode("Invalid request"));
    exit;
}

$id = $_GET['id'];

// Delete the leave request
$sql = "DELETE FROM lmleaverequest WHERE ID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);

// Delete the leave balance
$sql2 = "UPDATE lmleavebalance SET CurrentBalance = CurrentBalance + ?, Taken = Taken - ? WHERE ID = ?";
$stmt2 = $con->prepare($sql2);
$stmt2->bind_param("ddi", $originalRequest['LeaveDay'], $originalRequest['LeaveDay'], $id);

if ($stmt->execute() && $stmt2->execute()) {
    header("Location: ../../view/LeaveRequest/index.php?success=" . urlencode("Leave request deleted successfully"));
} else {
    header("Location: ../../view/LeaveRequest/index.php?error=" . urlencode("Failed to delete leave request"));
}

$stmt->close();
$stmt2->close();
$con->close();
?>
