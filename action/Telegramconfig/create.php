<?php
include("../../Config/conect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $token = $_POST['token'];
    $chat_id = $_POST['chat_id'];
    $status = $_POST['status'];

    $stmt = $con->prepare("INSERT INTO sytelegram_config (id, token, chat_id, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssi", $token, $chat_id, $status);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Config added", "id" => $stmt->insert_id]);
    } else {
        http_response_code(500);
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
