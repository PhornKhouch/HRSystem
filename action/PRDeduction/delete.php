<?php
include("../../Config/conect.php");
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
      $sql = "DELETE FROM prdeduction WHERE ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../../view/PRDeduction/index.php?success=" . urlencode("Deduction deleted successfully"));
    } else {
        header("Location: ../../view/PRDeduction/index.php?error=" . urlencode("Error deleting deduction: " . $con->error));
    }
    
    $stmt->close();
    $con->close();
} else {
    header("Location: ../../view/PRDeduction/index.php?error=" . urlencode("Invalid request"));
}
?>
