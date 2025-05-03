<?php
require '../../Config/conect.php';

// Get leave request ID
$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Leave request ID is required']);
    exit;
}

try {
    // Update leave request status
    $stmt = $con->prepare("UPDATE lmleaverequest SET Status = 'Rejected', UpdatedAt = NOW(),RejectedBy='HR Admin' WHERE ID = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
