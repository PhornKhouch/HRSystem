<?php
include("../../Config/conect.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $empCode = $_POST['empCode'];
    $deductType = $_POST['deductType'];
    $description = $_POST['description'];
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $remark = $_POST['remark'];

    $sql = "UPDATE prdeduction SET 
            EmpCode = ?, 
            DeductType = ?, 
            Description = ?, 
            FromDate = ?, 
            ToDate = ?, 
            Amount = ?, 
            Status = ?, 
            Remark = ? 
            WHERE ID = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssdssi", $empCode, $deductType, $description, $fromDate, $toDate, $amount, $status, $remark, $id);
      if ($stmt->execute()) {
        header("Location: ../../view/PRDeduction/index.php?success=" . urlencode("Deduction updated successfully"));
    } else {
        header("Location: ../../view/PRDeduction/index.php?error=" . urlencode("Error updating deduction: " . $con->error));
    }
    
    $stmt->close();
    $con->close();
} else {
    header("Location: ../../view/PRDeduction/index.php?error=" . urlencode("Invalid request"));
}
?>
