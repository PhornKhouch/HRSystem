<?php
session_start();
include("../../Config/conect.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "User") {
    $id = $_POST['id'];

    // Validate required field
    if (empty($id)) {
        echo json_encode(["status" => "error", "message" => "User ID is required"]);
        exit;
    }

    // Check if user exists
    $checkUser = $con->prepare("SELECT UserID FROM hrusers WHERE UserID = ?");
    $checkUser->bind_param("i", $id);
    $checkUser->execute();
    if ($checkUser->get_result()->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    // Prevent deletion of own account
    if (isset($_SESSION['UserID']) && $id == $_SESSION['UserID']) {
        echo json_encode(["status" => "error", "message" => "You cannot delete your own account"]);
        exit;
    }

    // Delete user
    $sql = "DELETE FROM hrusers WHERE UserID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting user: " . $con->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$con->close();
?>