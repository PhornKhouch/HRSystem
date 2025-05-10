<?php
include("../../Config/conect.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $empCode = $_POST['empCode'];
    $bonusType = $_POST['bonusType'];
    $description = $_POST['description'];
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $remark = $_POST['remark'];

    $sql = "UPDATE prbonus SET 
            EmpCode = ?, 
            BonusType = ?, 
            Description = ?, 
            FromDate = ?, 
            ToDate = ?, 
            Amount = ?, 
            Status = ?, 
            Remark = ? 
            WHERE ID = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssdssi", $empCode, $bonusType, $description, $fromDate, $toDate, $amount, $status, $remark, $id);
    
    if ($stmt->execute()) {
        header("Location: ../../view/PRBonus/index.php?success=" . urlencode("Bonus updated successfully"));
    } else {
        header("Location: ../../view/PRBonus/index.php?error=" . urlencode("Error updating bonus: " . $con->error));
    }
    
    $stmt->close();
    $con->close();
} else {
    header("Location: ../../view/PRBonus/index.php");
}
?>
