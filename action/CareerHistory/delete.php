<?php
include("../../Config/conect.php");
session_start();

try {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("No career history specified");
    }

    $id = $_GET['id'];

    // Get the career history details first (for RESIGN status check)
    $stmt = $con->prepare("SELECT CareerHistoryType, EmployeeID FROM careerhistory WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $career = $result->fetch_assoc();

    if (!$career) {
        throw new Exception("Career history not found");
    }

    // Delete the career history
    $deleteStmt = $con->prepare("DELETE FROM careerhistory WHERE ID = ?");
    $deleteStmt->bind_param("i", $id);

    if (!$deleteStmt->execute()) {
        throw new Exception("Error deleting career history: " . $deleteStmt->error);
    }

    // If this was a RESIGN entry, reactivate the employee
    if ($career['CareerHistoryType'] === 'RESIGN') {
        $updateStmt = $con->prepare("UPDATE hrstaffprofile SET Status = 'Active' WHERE EmpCode = ?");
        if ($updateStmt) {
            $updateStmt->bind_param("s", $career['EmployeeID']);
            $updateStmt->execute();
            $updateStmt->close();
        }
    }

    $deleteStmt->close();
    $stmt->close();

    // Redirect back with success message
    $message = urlencode("Career history deleted successfully!");
    header("Location: ../../view/CareerHistory/index.php?success=" . $message);
    exit();

} catch (Exception $e) {
    $error = urlencode($e->getMessage());
    header("Location: ../../view/CareerHistory/index.php?error=" . $error);
    exit();
}
