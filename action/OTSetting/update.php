<?php
include("../../Config/conect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "OTSetting") {
    $code = $_POST['code'];
    $description = $_POST['description'];
    $rate = $_POST['rate'];

    // Update OT setting
    $sql = "UPDATE protrate SET Des = ?, Rate = ? WHERE Code = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sds", $description, $rate, $code);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'OT Setting updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating OT Setting: ' . $stmt->error]);
    }

    $stmt->close();
}

$con->close();
?>