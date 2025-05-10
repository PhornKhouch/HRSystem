<?php
include("../../Config/conect.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empCode = $_POST['empCode'];
    $bonusType = $_POST['bonusType'];
    $description = $_POST['description'];
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $remark = $_POST['remark'];

    $sql = "INSERT INTO prbonus (EmpCode, BonusType, Description, FromDate, ToDate, Amount, Status, Remark) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssdss", $empCode, $bonusType, $description, $fromDate, $toDate, $amount, $status, $remark);
    
    if ($stmt->execute()) {
        header("Location: ../../view/PRBonus/index.php?success=" . urlencode("Bonus created successfully"));
    } else {
        header("Location: ../../view/PRBonus/index.php?error=" . urlencode("Error creating bonus: " . $con->error));
    }
    
    $stmt->close();
    $con->close();
} else {
    header("Location: ../../view/PRBonus/index.php");
}
?>
