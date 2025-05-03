<?php
session_start();
include("../../Config/conect.php");

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// // Verify CSRF token
// if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
//     !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
//     http_response_code(403);
//     echo json_encode([
//         'status' => 'error',
//         'message' => 'Invalid CSRF token',
//         'debug' => [
//             'post_token' => $_POST['csrf_token'] ?? 'not set',
//             'session_token' => $_SESSION['csrf_token'] ?? 'not set'
//         ]
//     ]);
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            throw new Exception('Username and password are required');
        }

        // Debug database connection
        if ($con->connect_error) {
            throw new Exception('Database connection failed: ' . $con->connect_error);
        }

        // First get user by username
        $stmt = $con->prepare("SELECT UserID, Username, Password, Role FROM hrusers WHERE Username = ? AND Status = 'active'");
        if (!$stmt) {
            throw new Exception('Database prepare error: ' . $con->error);
        }

        $stmt->bind_param("s", $username);
        if (!$stmt->execute()) {
            throw new Exception('Query execution error: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception('Invalid username or password');
        }

        $user = $result->fetch_assoc();
        
        // Compare password
        if ($password !== $user['Password']) {
            throw new Exception('Invalid username or password');
        }

        // Set session variables
        $_SESSION['user'] = [
            'id' => $user['UserID'],
            'username' => $user['Username'],
            'role' => $user['Role']
        ];

        // Generate new CSRF token
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful',
            'debug' => [
                'username' => $username,
                'role' => $user['Role']
            ]
        ]);

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
            'debug' => [
                'username' => $username ?? 'not set',
                'db_connected' => isset($con) && !$con->connect_error,
                'post_data' => $_POST
            ]
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
