<?php
include("../../Config/conect.php");
session_start();

try {
    // Validate required fields
    $requiredFields = ['employeeID', 'startDate', 'careerCode'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Please fill in all required fields");
        }
    }

    // Get form data
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

    // Insert into career history
    $stmt = $con->prepare("INSERT INTO careerhistory (
        ID, CareerHistoryType, EmployeeID, PositionTitle, Department,
        StartDate, EndDate, Remark, Increase,
        CreatedAt, UpdatedAt
    ) VALUES (
        NULL, ?, ?, ?, ?,
        ?, ?, ?, ?,
        CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
    )");

    if (!$stmt) {
        throw new Exception('Database error: ' . $con->error);
    }

    $stmt->bind_param("sssssssd",
        $careerCode, $employeeID, $positionTitle, $department,
        $startDate, $endDate, $remark, $increase
    );

    if (!$stmt->execute()) {
        throw new Exception('Error creating career history: ' . $stmt->error);
    }

    // If this is a RESIGN entry, update staff status
    if ($careerCode === 'RESIGN') {
        $updateStmt = $con->prepare("UPDATE hrstaffprofile SET Status = 'Inactive' WHERE EmpCode = ?");
        if ($updateStmt) {
            $updateStmt->bind_param("s", $employeeID);
            $updateStmt->execute();
            $updateStmt->close();
        }
    }

    $stmt->close();
    
    // Redirect back to index with success message
    $message = urlencode("Career History created successfully!");
    header("Location: ../../view/CareerHistory/index.php?success=" . $message);
    exit();

} catch (Exception $e) {
    // Handle any errors
    $error = urlencode($e->getMessage());
    header("Location: ../../view/CareerHistory/create.php?error=" . $error);
    exit();
}