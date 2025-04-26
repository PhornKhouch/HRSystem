<?php
include("../../Config/conect.php");
session_start();

try {
    // Validate required fields
    $requiredFields = ['id', 'employeeID', 'startDate', 'careerCode'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Please fill in all required fields");
        }
    }

    // Get form data
    $id = $_POST['id'];
    $employeeID = $_POST['employeeID'];
    $department = $_POST['department'];
    $positionTitle = $_POST['positionTitle'];
    $startDate = $_POST['startDate'];
    $endDate = !empty($_POST['endDate']) ? $_POST['endDate'] : null;
    $remark = !empty($_POST['remark']) ? $_POST['remark'] : null;
    $increase = !empty($_POST['increase']) ? $_POST['increase'] : null;
    $careerCode = $_POST['careerCode'];

    // Validate career code
    $validCareerCodes = ['NEW', 'PROMOTE', 'TRANSFER', 'RESIGN', 'INCREASE'];
    if (!in_array($careerCode, $validCareerCodes)) {
        throw new Exception("Invalid career code");
    }

    // Validate dates
    if ($endDate && $endDate < $startDate) {
        throw new Exception("End date cannot be earlier than start date");
    }

    // Validate increase amount for INCREASE career code
    if ($careerCode === 'INCREASE' && empty($increase)) {
        throw new Exception("Increase amount is required for salary increase");
    }

    // Get current career history data
    $checkStmt = $con->prepare("SELECT CareerHistoryType, EmployeeID FROM careerhistory WHERE ID = ?");
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $currentData = $result->fetch_assoc();
    $checkStmt->close();

    if (!$currentData) {
        throw new Exception("Career history not found");
    }

    // Begin transaction
    $con->begin_transaction();

    try {
        // Update career history
        $stmt = $con->prepare("UPDATE careerhistory SET 
            EmployeeID = ?, PositionTitle = ?, Department = ?,
            StartDate = ?, EndDate = ?, Remark = ?, Increase = ?,
            CareerHistoryType = ?, UpdatedAt = CURRENT_TIMESTAMP
            WHERE ID = ?");

        if (!$stmt) {
            throw new Exception("Database error: " . $con->error);
        }

        $stmt->bind_param("ssssssssi",
            $employeeID, $positionTitle, $department,
            $startDate, $endDate, $remark, $increase,
            $careerCode, $id
        );

        if (!$stmt->execute()) {
            throw new Exception("Error updating career history: " . $stmt->error);
        }

        // Handle employee status changes
        if ($careerCode === 'RESIGN') {
            // Check if this is the latest career history for the employee
            $latestStmt = $con->prepare("SELECT CareerHistoryType FROM careerhistory 
                                       WHERE EmployeeID = ? AND ID != ?
                                       ORDER BY StartDate DESC, CreatedAt DESC 
                                       LIMIT 1");
            $latestStmt->bind_param("si", $employeeID, $id);
            $latestStmt->execute();
            $latestResult = $latestStmt->get_result();
            $latestRow = $latestResult->fetch_assoc();
            $latestStmt->close();

            // If this is the latest entry or no other entries exist, set status to Inactive
            if (!$latestRow || $startDate >= $latestRow['StartDate']) {
                $updateStmt = $con->prepare("UPDATE hrstaffprofile SET Status = 'Inactive' WHERE EmpCode = ?");
                $updateStmt->bind_param("s", $employeeID);
                $updateStmt->execute();
                $updateStmt->close();
            }
        } elseif ($currentData['CareerHistoryType'] === 'RESIGN') {
            // If changing from RESIGN to another status, check if we should reactivate the employee
            $latestStmt = $con->prepare("SELECT CareerHistoryType FROM careerhistory 
                                       WHERE EmployeeID = ? AND CareerHistoryType = 'RESIGN'
                                       AND (StartDate > ? OR (StartDate = ? AND CreatedAt > CURRENT_TIMESTAMP))
                                       LIMIT 1");
            $latestStmt->bind_param("sss", $employeeID, $startDate, $startDate);
            $latestStmt->execute();
            $result = $latestStmt->get_result();
            $latestStmt->close();

            // If no later RESIGN entries exist, reactivate the employee
            if ($result->num_rows === 0) {
                $updateStmt = $con->prepare("UPDATE hrstaffprofile SET Status = 'Active' WHERE EmpCode = ?");
                $updateStmt->bind_param("s", $employeeID);
                $updateStmt->execute();
                $updateStmt->close();
            }
        }

        $con->commit();
        
        // Redirect with success message
        $message = urlencode("Career History updated successfully!");
        header("Location: ../../view/CareerHistory/index.php?success=" . $message);
        exit();

    } catch (Exception $e) {
        $con->rollback();
        throw $e;
    }

} catch (Exception $e) {
    // Handle any errors
    $error = urlencode($e->getMessage());
    header("Location: ../../view/CareerHistory/edit.php?id=" . urlencode($_POST['id']) . "&error=" . $error);
    exit();
}
