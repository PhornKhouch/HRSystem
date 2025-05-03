<?php
include("../../Config/conect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $token = $_POST['token'];
    $chat_id = $_POST['chat_id'];
    $status = $_POST['status'];

    $stmt = $con->prepare("UPDATE sytelegram_config SET token = ?, chat_id = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssii", $token, $chat_id, $status, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Config updated"]);
    } else {
        http_response_code(500);
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
