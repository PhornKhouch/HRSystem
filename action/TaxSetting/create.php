<?php
include("../../Config/conect.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "TaxSetting") {
    $amountFrom = $_POST['amountFrom'];
    $amountTo = $_POST['amountTo'];
    $rate = $_POST['rate'];
    $status = $_POST['status'];

    // Check for overlapping ranges
    $checkSql = "SELECT id FROM prtaxrate WHERE 
                (? BETWEEN AmountFrom AND AmountTo OR 
                 ? BETWEEN AmountFrom AND AmountTo OR
                 (AmountFrom BETWEEN ? AND ?) OR
                 (AmountTo BETWEEN ? AND ?))";
    $checkStmt = $con->prepare($checkSql);
    $checkStmt->bind_param("dddddd", $amountFrom, $amountTo, $amountFrom, $amountTo, $amountFrom, $amountTo);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Tax range overlaps with existing ranges"]);
        exit;
    }

    // Insert new tax setting
    $sql = "INSERT INTO prtaxrate (AmountFrom, AmountTo, rate, status) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("dddi", $amountFrom, $amountTo, $rate, $status);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Tax setting added successfully", "id" => $con->insert_id]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error adding tax setting: " . $stmt->error]);
    }

    $stmt->close();
}

$con->close();
?>