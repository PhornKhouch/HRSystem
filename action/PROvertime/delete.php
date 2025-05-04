<?php
include("../../Config/conect.php");
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM provertime WHERE ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../../view/PROvertime/index.php?success=" . urlencode("Overtime request deleted successfully"));
        exit();
    } else {
        header("Location: ../../view/PROvertime/index.php?error=" . urlencode("Error deleting overtime request"));
        exit();
    }
} else {
    header("Location: ../../view/PROvertime/index.php");
    exit();
}