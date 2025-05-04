<?php
include("../../Config/conect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "OTSetting") {
    $code = $_POST['code'];
    $description = $_POST['description'];
    $rate = $_POST['rate'];

    // Check if code already exists
    $checkSql = "SELECT Code FROM protrate WHERE Code = ?";
    $checkStmt = $con->prepare($checkSql);
    $checkStmt->bind_param("s", $code);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo "OT Setting code already exists";
        exit;
    }

    // Insert new OT setting
    $sql = "INSERT INTO protrate (Code, Des, Rate) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssd", $code, $description, $rate);

    if ($stmt->execute()) {
        echo "OT Setting added successfully";
    } else {
        echo "Error adding OT Setting: " . $stmt->error;
    }

    $stmt->close();
}

$con->close();
?>