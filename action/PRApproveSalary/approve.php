<?php
require '../../Config/conect.php';
session_start();

// Get salary approval ID
$id = $_GET['id'] ?? null;
$approver = $_SESSION['username'] ?? 'HR Admin'; // Get actual approver from session

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Salary approval ID is required']);
    exit;
}

try {
    // Start transaction
    $con->begin_transaction();

    // Get salary details
    $stmt = $con->prepare("SELECT * FROM prapprovesalary WHERE ID = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $salary = $result->fetch_assoc();

    if (!$salary) {
        throw new Exception('Salary record not found');
    }

    // Update salary approval status
    $remark = $_POST['remark'] ?? '';
    $now = date('Y-m-d H:i:s');
    
    $stmt = $con->prepare("UPDATE prapprovesalary 
        SET Status = 'Approved',
            Remark = ?,
            Actionby = ?,
            ActionDate = ?
        WHERE ID = ? AND Status = 'Pending'");
    $stmt->bind_param('sssi', $remark, $approver, $now, $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception('Failed to update salary approval status');
    }

    // Update status in prapprovesalary table
    $month = $salary['InMonth'];
    $status = 'Approved';
    $sql = "UPDATE prapprovesalary SET status = ? WHERE InMonth = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ss', $status, $month);

    if (!$stmt->execute()) {
        throw new Exception('Failed to update status');
    }

    // Commit transaction
    $con->commit();

    // echo json_encode([
    //     'status' => 'success',
    //     'message' => 'Salary has been approved successfully'
    // ]);
    header("Location: ../../view/PRApproveSalary/index.php?success=" . urlencode("Salary has been approved successfully"));
} catch (Exception $e) {
    // Rollback on error
    $con->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
