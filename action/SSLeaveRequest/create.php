<?php
include("../../Config/conect.php");
include 'telegram_helper.php';
session_start();

// Get form data
$empCode = $_POST['empCode'];
$leaveType = $_POST['leaveType'];
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$reason = $_POST['reason'];
$leaveDay = $_POST['leaveDay'];

// Validate dates
if (strtotime($toDate) < strtotime($fromDate)) {
    header("Location: ../../view/SSLeaveRequest/create.php?error=" . urlencode("To Date cannot be earlier than From Date"));
    exit;
}


if($leaveType!=null)
{
    // Get leave policy
    $leavePolicySql = "SELECT IsProbation, IsOverBalance FROM lmleavetype WHERE Code = ?";
    $policyStmt = $con->prepare($leavePolicySql);
    $policyStmt->bind_param("s", $leaveType);
    $policyStmt->execute();
    $policyResult = $policyStmt->get_result();
    $leavePolicy = $policyResult->fetch_assoc();

    // Check if employee is in probation period
    if ($leavePolicy['IsProbation'] == 0) {
        $probationSql = "SELECT IsProb FROM hrstaffprofile WHERE EmpCode = ?";
        $probStmt = $con->prepare($probationSql);
        $probStmt->bind_param("s", $empCode);
        $probStmt->execute();
        $probResult = $probStmt->get_result();
        $probStatus = $probResult->fetch_assoc();

        if ($probStatus['IsProb'] == 1) {
            header("Location: ../../view/SSLeaveRequest/create.php?error=" . urlencode("Cannot request leave during probation period"));
            exit;
        }
    }

    // Check leave balance if overbalance is not allowed
    if ($leavePolicy['IsOverBalance'] == 0) {
        $balanceSql = "SELECT CurrentBalance FROM lmleavebalance WHERE EmpCode = ? AND LeaveType = ? AND inyear = YEAR(CURRENT_DATE)";
        $balanceStmt = $con->prepare($balanceSql);
        $balanceStmt->bind_param("ss", $empCode, $leaveType);
        $balanceStmt->execute();
        $balanceResult = $balanceStmt->get_result();
        $balance = $balanceResult->fetch_assoc();

        if ($balance && $leaveDay > $balance['CurrentBalance']) {
            header("Location: ../../view/SSLeaveRequest/create.php?error=" . urlencode("Can not use over leave balance"));
            exit;
        }
    }
}

// Insert leave request
$sql = "INSERT INTO lmleaverequest (EmpCode, LeaveType, FromDate, ToDate, Reason, LeaveDay, Status) 
        VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
$stmt = $con->prepare($sql);
$stmt->bind_param("sssssd", $empCode, $leaveType, $fromDate, $toDate, $reason, $leaveDay);

//get employee name
$sql = "SELECT EmpName,Telegram FROM HRstaffprofile WHERE EmpCode = ?";
$empNameStmt = $con->prepare($sql);
$empNameStmt->bind_param("s", $empCode);
$empNameStmt->execute();
$empNameResult = $empNameStmt->get_result();
if ($empNameResult->num_rows > 0) {
    $row = $empNameResult->fetch_assoc();
    $empName = $row['EmpName'];
} else {
    header("Location: ../../view/SSLeaveRequest/create.php?error=" . urlencode("Employee not found"));
    exit;
}


//send telegram message
$telegram = $row['Telegram'];
$selctToken= "Select * from sytelegram_config Where chat_id='$telegram'";
$result= $con->query($selctToken);
$botToken  = $result['bot_token'];
$groupID =$telegram;
if(!empty($result)){
    $message = GetMessageForLeave($empName,$leaveType,$fromDate, $toDate,"Pending");
    sendTelegramMessage($message,$botToken,$groupID);
}


if ($stmt->execute()) {
    header("Location: ../../view/SSLeaveRequest/index.php?success=" . urlencode("Leave request created successfully"));
} else {
    header("Location: ../../view/SSLeaveRequest/create.php?error=" . urlencode("Failed to create leave request: " . $con->error));
}

$stmt->close();
$con->close();
?>
