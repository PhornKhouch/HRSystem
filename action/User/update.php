<?php
session_start();
include("../../Config/conect.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "User") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    // Validate required fields
    if (empty($id) || empty($username) || empty($email) || empty($role) || empty($status)) {
        echo json_encode(["status" => "error", "message" => "Required fields cannot be empty"]);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit;
    }

    // Check if username exists for other users
    $checkUsername = $con->prepare("SELECT UserID FROM hrusers WHERE Username = ? AND UserID != ?");
    $checkUsername->bind_param("si", $username, $id);
    $checkUsername->execute();
    if ($checkUsername->get_result()->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username already exists"]);
        exit;
    }

    // Check if email exists for other users
    $checkEmail = $con->prepare("SELECT UserID FROM hrusers WHERE Email = ? AND UserID != ?");
    $checkEmail->bind_param("si", $email, $id);
    $checkEmail->execute();
    if ($checkEmail->get_result()->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists"]);
        exit;
    }

    // Prepare base query
    if (!empty($password)) {
        // Update with new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE hrusers SET Username = ?, Email = ?, Password = ?, Role = ?, Status = ?, UpdatedAt = CURRENT_TIMESTAMP WHERE UserID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssi", $username, $email, $hashedPassword, $role, $status, $id);
    } else {
        // Update without changing password
        $sql = "UPDATE hrusers SET Username = ?, Email = ?, Role = ?, Status = ?, UpdatedAt = CURRENT_TIMESTAMP WHERE UserID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $role, $status, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating user: " . $con->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$con->close();
?>