<?php
include("../../Config/conect.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empcode = $_POST['empcode'];
    $ottype = $_POST['ottype'];
    $otdate = $_POST['otdate'];
    $fromtime = $_POST['fromtime'];
    $totime = $_POST['totime'];
    $reason = $_POST['reason'];

    // Calculate hours
    $from = new DateTime($fromtime);
    $to = new DateTime($totime);
    $interval = $from->diff($to);
    $hours = $interval->h + ($interval->i / 60);

    $sql = "INSERT INTO provertime (Empcode, OTType, OTDate, FromTime, ToTime, hour, Reason, Provtime) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssds", $empcode, $ottype, $otdate, $fromtime, $totime, $hours, $reason);
    
    if ($stmt->execute()) {
        header("Location: ../../view/PROvertime/index.php?success=" . urlencode("Overtime request created successfully"));
        exit();
    } else {
        header("Location: ../../view/PROvertime/create.php?error=" . urlencode("Error creating overtime request"));
        exit();
    }
}
?>