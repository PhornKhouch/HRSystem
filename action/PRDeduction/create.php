<?php
include("../../Config/conect.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empCode = $_POST['empCode'];
    $deductType = $_POST['deductType'];
    $description = $_POST['description'];
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $remark = $_POST['remark'];

    $sql = "INSERT INTO prdeduction (EmpCode, DeductType, Description, FromDate, ToDate, Amount, Status, Remark) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssdss", $empCode, $deductType, $description, $fromDate, $toDate, $amount, $status, $remark);
    
    if ($stmt->execute()) {
        header("Location: ../../view/PRDeduction/index.php?success=" . urlencode("Deduction created successfully"));
    } else {
        header("Location: ../../view/PRDeduction/index.php?error=" . urlencode("Error creating deduction: " . $con->error));
    }
    
    $stmt->close();
    $con->close();
} else {
    header("Location: ../../view/PRDeduction/index.php?error=" . urlencode("Invalid request"));
}
?>
