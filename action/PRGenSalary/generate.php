<?php
include("../../Config/conect.php");
include("function.php");

header('Content-Type: application/json');

try {
    // Get parameters
    $month = $_POST['month'] ?? '';
    $selectedStaff = $_POST['selectedStaff'] ?? [];

    if (empty($month)) {
        throw new Exception('Month is required');
    }
    if (empty($selectedStaff)) {
        throw new Exception('No employees selected');
    }    // Start transaction
    mysqli_begin_transaction($con);


    // if salary is approve cannot generate again 
    $Approvesalary = "Select * from prapprovesalary where InMonth = '$month'";
    $res= $con->query($Approvesalary);
    $rowData= $res->fetch_assoc();
   if(!empty($rowData)){
     $StatusApprove = $rowData['status'];
     if($StatusApprove=="Approved"){
        throw new Exception("Salary is lock Can not regenerate");
     }
   }


    $processedEmployees = [];
    $errors = [];

    // Process each selected employee
    foreach ($selectedStaff as $empCode) {
        try {
            // Get employee basic information
            $emp_sql = "SELECT *
                        FROM hrstaffprofile  
                        WHERE EmpCode = ? AND Status = 'Active'";

            $stmt = mysqli_prepare($con, $emp_sql);
            mysqli_stmt_bind_param($stmt, "s", $empCode);
            mysqli_stmt_execute($stmt);
            $emp_result = mysqli_stmt_get_result($stmt);

            if ($emp_row = mysqli_fetch_assoc($emp_result)) {
                $basicSalary = floatval($emp_row['Salary']);
                // Calculate overtime
                $workingDays = 26; // Default working days per month
                $workingHours = 8; // Default working hours per day

                // Get working days and hours from pay policy if available
                if (isset($emp_row['PayParameter']) && !empty($emp_row['PayParameter'])) {
                    $payParamQuery = "SELECT workday, hourperday FROM prpaypolicy WHERE Code = ?";
                    $stmt_param = mysqli_prepare($con, $payParamQuery);
                    if ($stmt_param) {
                        mysqli_stmt_bind_param($stmt_param, "s", $emp_row['PayParameter']);
                        mysqli_stmt_execute($stmt_param);
                        $res = mysqli_stmt_get_result($stmt_param);
                        $row = mysqli_fetch_assoc($res);

                        if ($row) {
                            if (isset($row['workday']) && $row['workday'] > 0) {
                                $workingDays = $row['workday'];
                            }
                            if (isset($row['hourperday']) && $row['hourperday'] > 0) {
                                $workingHours = $row['hourperday'];
                            }
                        }
                        mysqli_stmt_close($stmt_param);
                    }
                }

                // Calculate overtime based on working days and hours
                $totalOT = CalculateOvertime($empCode, $basicSalary, $workingDays, $workingHours, $month, $con);
                // Calculate allowances
                $totalAllowance = CalculateAllowance($empCode, $month, $con);

                // Calculate bonus
                $totalBonus = CalculateBonus($empCode, $month, $con);

                // Calculate deductions
                $totalDeduction = CalculateDeduction($empCode, $month, $con);

                // Calculate gross and net salary
                $grossSalary = $basicSalary + $totalAllowance + $totalOT + $totalBonus;
                $netSalary = $grossSalary - $totalDeduction;                // Check if salary record already exists
                $check_sql = "SELECT ID FROM hisgensalary 
                             WHERE EmpCode = ? AND InMonth = ?";
                $stmt = mysqli_prepare($con, $check_sql);
                mysqli_stmt_bind_param($stmt, "ss", $empCode, $month);
                mysqli_stmt_execute($stmt);
                $check_result = mysqli_stmt_get_result($stmt);

                // Calculate tax components
                $leavedTax = 0; // This should be calculated based on your tax rules
                $amtobetax = 0; // This should be calculated based on your tax rules
                $family = 0; // This should be based on employee's family allowance
                $untaxAm = 0; // Untaxed amount
                $nssf = 5.98; // National Social Security Fund contribution
                //Tax calculation
                $AmountCaleTax =$grossSalary*4000;
                $TaxAmountKH = CalculateTax($AmountCaleTax, $empCode, $month, $con);
                $TaxUSD=$TaxAmountKH/4000;
                $amtobetax= $TaxUSD;
                $untaxAm=$grossSalary-$amtobetax;
                $netSalary=$untaxAm-$nssf;
                // Extract year and month
                $inYear = intval(date('Y', strtotime($month)));
                $inMonth = date('Y-m', strtotime($month));

                if (mysqli_num_rows($check_result) > 0) {                    // Update existing record
                    $update_sql = "UPDATE hisgensalary SET 
                                  Salary = ?,
                                  Allowance = ?,
                                  OT = ?,
                                  Bonus = ?,
                                  Dedction = ?,
                                  LeavedTax = ?,
                                  Amtobetax = ?,
                                  Grosspay = ?,
                                  Family = ?,
                                  UntaxAm = ?,
                                  NSSF = ?,
                                  NetSalary = ?
                                  WHERE EmpCode = ? AND InMonth = ?";

                    $stmt = mysqli_prepare($con, $update_sql);
                    mysqli_stmt_bind_param(
                        $stmt,
                        "ddddddddddddss",
                        $basicSalary,
                        $totalAllowance,
                        $totalOT,
                        $totalBonus,
                        $totalDeduction,
                        $leavedTax,
                        $amtobetax,
                        $grossSalary,
                        $family,
                        $untaxAm,
                        $nssf,
                        $netSalary,
                        $empCode,
                        $inMonth
                    );
                    mysqli_stmt_execute($stmt);
                } else {                    // Insert new record
                    $insert_sql = "INSERT INTO hisgensalary (
                                  EmpCode, InMonth, Inyear, Salary, 
                                  Allowance, OT, Bonus, Dedction, LeavedTax,
                                  Amtobetax, Grosspay, Family, UntaxAm,
                                  NSSF, NetSalary) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = mysqli_prepare($con, $insert_sql);
                    mysqli_stmt_bind_param(
                        $stmt,
                        "ssidddddddddddd",
                        $empCode,
                        $inMonth,
                        $inYear,
                        $basicSalary,
                        $totalAllowance,
                        $totalOT,
                        $totalBonus,
                        $totalDeduction,
                        $leavedTax,
                        $amtobetax,
                        $grossSalary,
                        $family,
                        $untaxAm,
                        $nssf,
                        $netSalary
                    );
                    mysqli_stmt_execute($stmt);
                }

                // Add to processed employees array
                $processedEmployees[] = array(
                    'emp_code' => $empCode,
                    'emp_name' => $emp_row['EmpName'],
                    'department' => $emp_row['Department'],
                    'salary' => number_format($basicSalary, 2),
                    'allowance' => number_format($totalAllowance, 2),
                    'bonus' => number_format($totalBonus, 2),
                    'deduction' => number_format($totalDeduction, 2),
                    'leaved_tax' => number_format($leavedTax, 2),
                    'amtobetax' => number_format($amtobetax, 2),
                    'grosspay' => number_format($grossSalary, 2),
                    'family' => number_format($family, 2),
                    'untax_am' => number_format($untaxAm, 2),
                    'nssf' => number_format($nssf, 2),
                    'net_salary' => number_format($netSalary, 2)
                );
            } else {
                $errors[] = "Employee not found or inactive: " . $empCode;
            }            //add to table approval salary

        } catch (Exception $e) {
            $errors[] = "Error processing employee {$empCode}: " . $e->getMessage();
        }
    }

    // Check if we had any successful processing
    if (empty($processedEmployees)) {
        throw new Exception("No employees could be processed.\n" . implode("\n", $errors));
    }



    // Check if the month already exists in prsalaryapproval
    $checkApproval_sql = "SELECT * FROM prapprovesalary WHERE InMonth = ?";
    $stmt = mysqli_prepare($con, $checkApproval_sql);
    mysqli_stmt_bind_param($stmt, "s", $month);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($check_result) == 0) {
        // Insert into prsalaryapproval table
        $insertApproval_sql = "INSERT INTO prapprovesalary  (InMonth, Status) 
                                        VALUES ('$month', 'Pending')";
        $stmt = mysqli_prepare($con, $insertApproval_sql);
        mysqli_stmt_execute($stmt);
        if (!$stmt) {
            throw new Exception("Failed to insert into prapprovesalary: " . mysqli_error($con));
        }
        if (mysqli_affected_rows($con) <= 0) {
            throw new Exception("Failed to insert into prapprovesalary: No rows affected");
        }
    }




    // Commit transaction if we have any successful processing
    mysqli_commit($con);
    // Return success with any warnings
    $message = 'Salary calculation completed for ' . count($processedEmployees) . ' employee(s)';
    if (!empty($errors)) {
        $message .= "\nWarnings:\n" . implode("\n", $errors);
    }

    echo json_encode([
        'status' => 'success',
        'message' => $message,
        'data' => $processedEmployees,
        'warnings' => $errors
    ]);
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($con);

    echo json_encode([
        'status' => 'error',
        'message' => 'Error generating salary: ' . $e->getMessage()
    ]);
}

mysqli_close($con);
