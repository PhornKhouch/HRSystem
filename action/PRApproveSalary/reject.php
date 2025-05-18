<?php
require '../../Config/conect.php';

// Get salary approval ID
$id = $_POST['id'] ?? null;
$approver = $_SESSION['username'] ?? 'HR Admin';

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Salary approval ID is required']);
    exit;
}

try {
    $remark = $_POST['remark'] ?? '';
    if (empty($remark)) {
        throw new Exception('Remark is required for rejection');
    }

    // Start transaction
    $con->begin_transaction();

    // Get salary details first
    $stmt = $con->prepare("SELECT * FROM prsalaryapproval WHERE ID = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $salary = $result->fetch_assoc();

    if (!$salary) {
        throw new Exception('Salary record not found');
    }

    if ($salary['Status'] !== 'Pending') {
        throw new Exception('Salary has already been processed');
    }

    // Update salary approval status
    $now = date('Y-m-d H:i:s');
    $stmt = $con->prepare("UPDATE prsalaryapproval 
        SET Status = 'Rejected',
            Remark = ?,
            ApprovedBy = ?,
            ApprovedDate = ?
        WHERE ID = ? AND Status = 'Pending'");
    $stmt->bind_param('sssi', $remark, $approver, $now, $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception('Failed to update salary status');
    }

    // Update salary history
    $stmt = $con->prepare("INSERT INTO hisgensalary (
        EmpCode, InMonth, BasicSalary, TotalAllowance, 
        TotalDeduction, NetSalary, Status, ApprovedBy, 
        ApprovedDate, Remark
    ) SELECT 
        EmpCode, InMonth, BasicSalary, TotalAllowance,
        TotalDeduction, NetSalary, 'Rejected', ?, ?, ?
    FROM prsalaryapproval WHERE ID = ?");
    $stmt->bind_param('sssi', $approver, $now, $remark, $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception('Failed to update salary history');
    }

    // Commit transaction
    $con->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Salary has been rejected successfully'
    ]);
} catch (Exception $e) {
    // Rollback on error
    if (isset($con)) $con->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
