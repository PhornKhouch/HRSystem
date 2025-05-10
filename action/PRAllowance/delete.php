<?php
include("../../Config/conect.php");
session_start();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Prepare delete statement to prevent SQL injection
    $sql = "DELETE FROM prallowance WHERE ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../../view/PRAllowance/index.php?success=" . urlencode("Allowance deleted successfully"));
    } else {
        header("Location: ../../view/PRAllowance/index.php?error=" . urlencode("Error deleting allowance: " . $con->error));
    }
    
    $stmt->close();
} else {
    header("Location: ../../view/PRAllowance/index.php?error=" . urlencode("Invalid allowance ID"));
}

$con->close();