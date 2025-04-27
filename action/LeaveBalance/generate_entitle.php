<?php
session_start();
include("../../Config/conect.php");

header('Content-Type: application/json');

// Validate year parameter
if (!isset($_POST['year'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Year parameter is required'
    ]);
    exit;
}

$year = intval($_POST['year']);
$currentYear = date('Y');

// Validate year range
if ($year < ($currentYear - 2) || $year > ($currentYear + 1)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid year selected'
    ]);
    exit;
}

try {
    // Get all active staff with their join date
    $staffQuery = "SELECT EmpCode, Gender, startDate FROM hrstaffprofile WHERE Status = 'Active'";
    $staffResult = mysqli_query($con, $staffQuery);

    if (!$staffResult) {
        throw new Exception("Error fetching staff: " . mysqli_error($con));
    }

    // Get leave types
    $leaveTypesQuery = "SELECT Code as LeaveType, LeaveType as leave_type_name, default_balance 
                        FROM lmleavetype";
    $leaveTypesResult = mysqli_query($con, $leaveTypesQuery);

    if (!$leaveTypesResult) {
        throw new Exception("Error fetching leave types: " . mysqli_error($con));
    }

    $leaveTypes = [];
    while ($type = mysqli_fetch_assoc($leaveTypesResult)) {
        $leaveTypes[] = $type;
    }

    if (empty($leaveTypes)) {
        throw new Exception("No leave types found");
    }

    // Begin transaction
    mysqli_begin_transaction($con);

    $inserted = 0;
    $errors = [];

    // Process each staff member
    while ($staff = mysqli_fetch_assoc($staffResult)) {
        foreach ($leaveTypes as $leaveType) {
            if($staff['Gender'] == 'Male' && $leaveType['LeaveType'] == 'ML') {
                continue;
            }
            // Skip UL leave for all staff
            if ($leaveType['LeaveType'] != 'UL') {
                // Check if leave balance already exists for this combination
                $checkQuery = "SELECT id FROM lmleavebalance 
                             WHERE EmpCode = ? AND LeaveType = ? AND inyear = ?";
                $stmt = mysqli_prepare($con, $checkQuery);

                if (!$stmt) {
                    throw new Exception("Error preparing check query: " . mysqli_error($con));
                }

                mysqli_stmt_bind_param($stmt, "ssi", $staff['EmpCode'], $leaveType['LeaveType'], $year);
                mysqli_stmt_execute($stmt);
                $existingResult = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($existingResult) == 0)  {
                    $created_at = "$year-01-01 00:00:00";
                    $taken = 0;

                    if ($leaveType['LeaveType'] == 'AL') {
                        // Calculate prorated leave balance for AL only
                        $joinDate = new DateTime($staff['startDate']);
                        $yearStart = new DateTime("$year-01-01");
                        $yearEnd = new DateTime("$year-12-31");
                        $currentDate = new DateTime(); // Get current date
                        
                        // Use the later date between join date and year start
                        $startDate = $joinDate > $yearStart ? $joinDate : $yearStart;
                        
                        // Calculate full year entitlement
                        $totalMonths = ($yearEnd->format('Y') - $startDate->format('Y')) * 12;
                        $totalMonths += $yearEnd->format('n') - $startDate->format('n');
                        $totalMonths += 1; // Include both start and end month
                        
                        // Calculate prorated balance for full year (1.5 days per month)
                        $proratedBalance = $totalMonths * 1.5;
                        
                        // Cap at default balance
                        $balance = min($proratedBalance, $leaveType['default_balance']);
                        
                        // Calculate current entitlement up to current date
                        $endDate = $currentDate < $yearEnd ? $currentDate : $yearEnd;
                        $currentMonths = ($endDate->format('Y') - $startDate->format('Y')) * 12;
                        $currentMonths += $endDate->format('n') - $startDate->format('n');
                        $currentMonths += 1; // Include both start and end month
                        
                        // Calculate current balance (1.5 days per month)
                        $currentProratedBalance = $currentMonths * 1.5;
                        
                        // Cap current balance at the calculated full year balance
                        $currentBalance = min($currentProratedBalance, $balance);
                    } else {
                        // For non-AL leave types, use default balance without proration
                        $balance = $leaveType['default_balance'];
                        $currentBalance = $balance;
                    }

                    $entitle = $balance; // Set entitle to full year calculated balance
                    
                    // Insert new leave balance
                    $insertQuery = "INSERT INTO lmleavebalance 
                                 (EmpCode, LeaveType, Balance, Entitle, CurrentBalance, Taken, 
                                  created_at, inyear) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($con, $insertQuery);

                    if (!$stmt) {
                        throw new Exception("Error preparing insert query: " . mysqli_error($con));
                    }

                    mysqli_stmt_bind_param(
                        $stmt,
                        "ssdddiis",
                        $staff['EmpCode'],
                        $leaveType['LeaveType'],
                        $balance,
                        $entitle,
                        $currentBalance,
                        $taken,
                        $created_at,
                        $year
                    );

                    if (!mysqli_stmt_execute($stmt)) {
                        $errors[] = "Error inserting record for {$staff['EmpCode']}, {$leaveType['LeaveType']}: " . mysqli_stmt_error($stmt);
                        continue;
                    }

                    $inserted++;
                }
            }
        }
    }

    if (!empty($errors)) {
        throw new Exception("Encountered errors: " . implode("; ", $errors));
    }

    // Commit transaction
    mysqli_commit($con);

    echo json_encode([
        'status' => 'success',
        'message' => "Leave entitlements for year $year generated successfully. Added $inserted records."
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($con);

    echo json_encode([
        'status' => 'error',
        'message' => 'Error generating leave entitlements: ' . $e->getMessage()
    ]);
}

mysqli_close($con);
