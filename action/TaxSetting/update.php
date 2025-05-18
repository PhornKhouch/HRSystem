<?php
include("../../Config/conect.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "TaxSetting") {
    $id = $_POST['id'];
    $amountFrom = $_POST['amountFrom'];
    $amountTo = $_POST['amountTo'];
    $rate = $_POST['rate'];
    $status = $_POST['status'];

    // Check for overlapping ranges excluding current record
    $checkSql = "SELECT id FROM prtaxrate WHERE 
                id != ? AND
                (? BETWEEN AmountFrom AND AmountTo OR 
                 ? BETWEEN AmountFrom AND AmountTo OR
                 (AmountFrom BETWEEN ? AND ?) OR
                 (AmountTo BETWEEN ? AND ?))";
    $checkStmt = $con->prepare($checkSql);
    $checkStmt->bind_param("idddddd", $id, $amountFrom, $amountTo, $amountFrom, $amountTo, $amountFrom, $amountTo);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Tax range overlaps with existing ranges"]);
        exit;
    }

    // Update tax setting
    $sql = "UPDATE prtaxrate SET AmountFrom = ?, AmountTo = ?, rate = ?, status = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("dddii", $amountFrom, $amountTo, $rate, $status, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Tax setting updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating tax setting: " . $stmt->error]);
    }

    $stmt->close();
}

$con->close();
?>