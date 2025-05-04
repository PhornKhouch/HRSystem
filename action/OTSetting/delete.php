<?php
include("../../Config/conect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "OTSetting") {
    $code = $_POST['code'];

    // Delete OT setting
    $sql = "DELETE FROM protrate WHERE Code = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $code);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'OT Setting deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting OT Setting: ' . $stmt->error]);
    }

    $stmt->close();
}

$con->close();
?>