<?php
include("../../Config/conect.php");
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM prbonus WHERE ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../../view/PRBonus/index.php?success=" . urlencode("Bonus deleted successfully"));
    } else {
        header("Location: ../../view/PRBonus/index.php?error=" . urlencode("Error deleting bonus: " . $con->error));
    }
    
    $stmt->close();
    $con->close();
} else {
    header("Location: ../../view/PRBonus/index.php?error=" . urlencode("Invalid request"));
}
?>
