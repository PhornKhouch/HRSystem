<?php
include("../../Config/conect.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "TaxSetting") {
    $id = $_POST['id'];

    // Delete tax setting
    $sql = "DELETE FROM prtaxrate WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Tax setting deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting tax setting: " . $stmt->error]);
    }

    $stmt->close();
}

$con->close();
?>