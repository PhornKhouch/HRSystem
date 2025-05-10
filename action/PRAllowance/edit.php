<?php
include("../../Config/conect.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $id = intval($_POST['ID']);
    $empCode = $con->real_escape_string($_POST['EmpCode']);
    $allowanceType = $con->real_escape_string($_POST['AllowanceType']);
    $description = $con->real_escape_string($_POST['Description']);
    $fromDate = $con->real_escape_string($_POST['FromDate']);
    $toDate = $con->real_escape_string($_POST['ToDate']);
    $amount = floatval($_POST['Amount']);
    $status = $con->real_escape_string($_POST['Status']);
    $remark = $con->real_escape_string($_POST['Remark'] ?? '');

    // Validate dates
    if (strtotime($fromDate) > strtotime($toDate)) {
        header("Location: ../../view/PRAllowance/edit.php?id=$id&error=" . urlencode("From Date cannot be later than To Date"));
        exit();
    }

    // Validate amount
    if ($amount <= 0) {
        header("Location: ../../view/PRAllowance/edit.php?id=$id&error=" . urlencode("Amount must be greater than 0"));
        exit();
    }

    // Update database
    $sql = "UPDATE prallowance SET 
            EmpCode = ?,
            AllowanceType = ?,
            Description = ?,
            FromDate = ?,
            ToDate = ?,
            Amount = ?,
            Status = ?,
            Remark = ?
            WHERE ID = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssdssi", 
        $empCode, 
        $allowanceType, 
        $description, 
        $fromDate, 
        $toDate, 
        $amount, 
        $status, 
        $remark,
        $id
    );

    if ($stmt->execute()) {
        header("Location: ../../view/PRAllowance/index.php?success=" . urlencode("Allowance updated successfully"));
    } else {
        header("Location: ../../view/PRAllowance/edit.php?id=$id&error=" . urlencode("Error updating allowance: " . $con->error));
    }

    $stmt->close();
} else {
    header("Location: ../../view/PRAllowance/index.php");
}

$con->close();
?>