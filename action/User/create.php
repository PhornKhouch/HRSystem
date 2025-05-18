<?php
session_start();
include("../../Config/conect.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "User") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    // Validate required fields
    if (empty($username) || empty($email) || empty($password) || empty($role) || empty($status)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit;
    }

    // Check if username already exists
    $checkUsername = $con->prepare("SELECT UserID FROM hrusers WHERE Username = ?");
    $checkUsername->bind_param("s", $username);
    $checkUsername->execute();
    if ($checkUsername->get_result()->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username already exists"]);
        exit;
    }

    // Check if email already exists
    $checkEmail = $con->prepare("SELECT UserID FROM hrusers WHERE Email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    if ($checkEmail->get_result()->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists"]);
        exit;
    }

    // Hash password
    //$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $hashedPassword = $password;
    // Insert new user
    $sql = "INSERT INTO hrusers (Username, Email, Password, Role, Status, CreatedAt) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $username, $email, $hashedPassword, $role, $status);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User added successfully", "id" => $con->insert_id]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error adding user: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

$con->close();
?>