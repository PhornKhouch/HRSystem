<?php
include("../../Config/conect.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $empCode = $_POST['id'];
        
        // Start transaction
        $con->begin_transaction();
        
        // Get photo and document paths before deleting
        $stmt = $con->prepare("SELECT Photo FROM hrstaffprofile WHERE EmpCode = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }

        $stmt->bind_param("s", $empCode);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $photoPath = null;
        if ($row = $result->fetch_assoc()) {
            $photoPath = $row['Photo'];
        }
        $stmt->close();

        // Get document paths
        $docStmt = $con->prepare("SELECT Photo FROM hrstaffdocument WHERE EmpCode = ?");
        if (!$docStmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }

        $docStmt->bind_param("s", $empCode);
        if (!$docStmt->execute()) {
            throw new Exception("Execute failed: " . $docStmt->error);
        }

        $docResult = $docStmt->get_result();
        $documentPaths = [];
        while ($row = $docResult->fetch_assoc()) {
            if (!empty($row['Photo'])) {
                $documentPaths[] = $row['Photo'];
            }
        }
        $docStmt->close();

        // Delete related records first
        // Delete from hrfamily
        $familyStmt = $con->prepare("DELETE FROM hrfamily WHERE EmpCode = ?");
        if (!$familyStmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }
        $familyStmt->bind_param("s", $empCode);
        if (!$familyStmt->execute()) {
            throw new Exception("Execute failed: " . $familyStmt->error);
        }
        $familyStmt->close();

        // Delete from hrstaffdocument
        $docDelStmt = $con->prepare("DELETE FROM hrstaffdocument WHERE EmpCode = ?");
        if (!$docDelStmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }
        $docDelStmt->bind_param("s", $empCode);
        if (!$docDelStmt->execute()) {
            throw new Exception("Execute failed: " . $docDelStmt->error);
        }
        $docDelStmt->close();

        // Finally delete the main record
        $stmt = $con->prepare("DELETE FROM hrstaffprofile WHERE EmpCode = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }

        $stmt->bind_param("s", $empCode);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        if ($stmt->affected_rows > 0) {
            // Delete profile photo if it exists
            if ($photoPath && file_exists("../../" . $photoPath)) {
                unlink("../../" . $photoPath);
            }

            // Delete document files
            foreach ($documentPaths as $docPath) {
                if (file_exists("../../" . $docPath)) {
                    unlink("../../" . $docPath);
                }
            }
            
            // Commit transaction
            $con->commit();
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Employee deleted successfully'
            ]);
        } else {
            // Rollback transaction
            $con->rollback();
            
            echo json_encode([
                'status' => 'error',
                'message' => 'Employee not found'
            ]);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        // Rollback transaction
        if ($con->connect_errno === 0) {
            $con->rollback();
        }
        
        error_log("Error in delete.php: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'Error deleting employee: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request'
    ]);
}
