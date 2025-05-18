<?php      

    function CalculateOvertime($empcode, $BasicSalary, $workday, $workhour, $month, $connection) {
        // Validate input parameters
        if ($workday <= 0 || $workhour <= 0) {
            error_log("Invalid workday ($workday) or workhour ($workhour) for employee $empcode");
            return 0;
        }

        // Get all overtime records for the employee in the specified month
        $select = "SELECT po.*, ot.Rate 
                  FROM provertime po 
                  LEFT JOIN protrate ot ON po.OTType = ot.Code 
                  WHERE po.EmpCode = ? 
                  AND DATE_FORMAT(po.OTDate, '%Y-%m') = ?";
                  
        $stmt = mysqli_prepare($connection, $select);
        mysqli_stmt_bind_param($stmt, "ss", $empcode, $month);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $totalOvertimePay = 0;
        $dailyRate = $BasicSalary / $workday;
        $hourlyRate = $dailyRate / $workhour;

        while ($row = mysqli_fetch_assoc($result)) {
            // Get OT rate from otsetting table, default to 1 if not found
            $otRate = $row['rate'] ?? 1;
            
            // Calculate hours between FromTime and ToTime
            $fromTime = strtotime($row['FromTime']);
            $toTime = strtotime($row['ToTime']);
            $otHours = ($toTime - $fromTime) / 3600; // Convert seconds to hours
            
            // Calculate overtime pay for this record
            $overtimePay = $hourlyRate * $otRate * $otHours;
            $totalOvertimePay += $overtimePay;
        }

        mysqli_stmt_close($stmt);
        return $totalOvertimePay;
    }    
    
    function CalculateAllowance($empcode, $month, $connection) {
        // Get all active allowances for the employee that are valid in the specified month
        $select = "SELECT SUM(Amount) as total_allowance 
                  FROM prallowance 
                  WHERE EmpCode = ? 
                  AND Status = 'Active'
                  AND DATE_FORMAT(FromDate, '%Y-%m') <= ?
                  AND (
                      DATE_FORMAT(ToDate, '%Y-%m') >= ? 
                      OR ToDate IS NULL
                  )";
                  
        $stmt = mysqli_prepare($connection, $select);
        mysqli_stmt_bind_param($stmt, "sss", $empcode, $month, $month);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        $totalAllowance = $row['total_allowance'] ?? 0;
        
        mysqli_stmt_close($stmt);
        return $totalAllowance;
    }



    function CalculateBonus($empcode, $month, $connection) {
        // Get all active bonuses for the employee that are valid in the specified month
        $select = "SELECT SUM(Amount) as total_bonus 
                  FROM prbonus 
                  WHERE EmpCode = ? 
                  AND Status = 'Active'
                  AND DATE_FORMAT(FromDate, '%Y-%m') <= ?
                  AND (
                      DATE_FORMAT(ToDate, '%Y-%m') >= ? 
                      OR ToDate IS NULL
                  )";
                  
        $stmt = mysqli_prepare($connection, $select);
        mysqli_stmt_bind_param($stmt, "sss", $empcode, $month, $month);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        $totalBonus = $row['total_bonus'] ?? 0;
        
        mysqli_stmt_close($stmt);
        return $totalBonus;
    }

    function CalculateDeduction($empcode, $month, $connection) {
        // Get all active deductions for the employee that are valid in the specified month
        $select = "SELECT SUM(Amount) as total_deduction 
                  FROM prdeduction 
                  WHERE EmpCode = ? 
                  AND Status = 'Active'
                  AND DATE_FORMAT(FromDate, '%Y-%m') <= ?
                  AND (
                      DATE_FORMAT(ToDate, '%Y-%m') >= ? 
                      OR ToDate IS NULL
                  )";
                  
        $stmt = mysqli_prepare($connection, $select);
        mysqli_stmt_bind_param($stmt, "sss", $empcode, $month, $month);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        $totalDeduction = $row['total_deduction'] ?? 0;
        
        mysqli_stmt_close($stmt);
        return $totalDeduction;
    }



    function CalculateSalary($empcode, $BasicSalary, $totalAllowance, $totalOvertimePay, $totalBonus, $totalDeduction) {
        // Calculate gross salary
        $grossSalary = $BasicSalary + $totalAllowance + $totalOvertimePay + $totalBonus;
        
        // Calculate net salary
        $netSalary = $grossSalary - $totalDeduction;
        
        return [
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary
        ];
    }   


    function CalculateTax($grossSalary,$empcode, $month, $connection) {
        //select from family
        $SQL= "Select * from hrfamily Where empcode = '$empcode' and IsTax=1";
        $result = mysqli_query($connection, $SQL);
        $row = mysqli_fetch_assoc($result);
        
        if(!empty($row)){
            $family = $row['IsTax'];
            if($family == 1){
                $taxExpected = 15000000;
                $grossSalary= $grossSalary - $taxExpected;
            }
        }
        // Get tax rate for the gross salary amount
        $select = "SELECT * FROM prtaxrate WHERE ? BETWEEN AmountFrom AND AmountTo AND status = 1";
        $stmt = mysqli_prepare($connection, $select);
        mysqli_stmt_bind_param($stmt, "d", $grossSalary);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        // Get tax rate from row, default to 0 if no matching tax bracket found
        $taxRate = !empty($row) ? $row['rate'] : 0;
        
        // Calculate total tax
        $totalTax = ($taxRate * $grossSalary) / 100;
        mysqli_stmt_close($stmt);
        return $totalTax;
    }
?>