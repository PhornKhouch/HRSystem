<?php
include("../../root/Header.php");
include("../../Config/conect.php");

// Check if user has permission
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../../login.php");
//     exit;
// }

// Fetch dashboard data
$currentYear = date('Y');
$currentMonth = date('m');

// Get total employee count
$stmt = $con->prepare("SELECT COUNT(*) as count FROM hrstaffprofile WHERE Status = 'Active'");
$stmt->execute();
$result = $stmt->get_result();
$headCount = $result->fetch_assoc()['count'];

// Get average employee age
$stmt = $con->prepare("SELECT AVG(TIMESTAMPDIFF(YEAR, Dob, CURDATE())) as avg_age FROM hrstaffprofile WHERE Status = 'Active'");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$avgAge = round($row['avg_age'] ?? 0);

// Get gender 
$stmt = $con->prepare("select Count(*) from hrstaffprofile where Gender='Female'");
$stmt->execute();
$result = $stmt->get_result();
//$FemaleCount = $result->fetch_assoc()['count'];

// Get HR to Employee ratio
$stmt = $con->prepare("SELECT COUNT(*) as hr_count FROM hrstaffprofile WHERE Department = 'Human Resources' AND Status = 'Active'");
$stmt->execute();
$result = $stmt->get_result();
$hrCount = $result->fetch_assoc()['hr_count'];
$hrRatio = ($hrCount / $headCount) * 100;

// Get department counts
$stmt = $con->prepare("SELECT Department, COUNT(*) as count FROM hrstaffprofile WHERE Status = 'Active' GROUP BY Department");
$stmt->execute();
$result = $stmt->get_result();
$departmentData = [];
while ($row = $result->fetch_assoc()) {
    $departmentData[] = $row;
}
// Get salary range distribution
$stmt = $con->prepare("SELECT 
    CASE 
        WHEN CAST(Salary AS DECIMAL(10,2)) <= 500 THEN '<500'
        WHEN CAST(Salary AS DECIMAL(10,2)) <= 1000 THEN '1000-500'
        WHEN CAST(Salary AS DECIMAL(10,2)) <= 2000 THEN '2000-1000'
        ELSE '>80000'
    END as salary_range,
    COUNT(*) as count
    FROM hrstaffprofile 
    WHERE Status = 'Active'
    GROUP BY 
    CASE 
        WHEN CAST(Salary AS DECIMAL(10,2)) <= 500 THEN '<500'
        WHEN CAST(Salary AS DECIMAL(10,2)) <= 1000 THEN '1000-500'
        WHEN CAST(Salary AS DECIMAL(10,2)) <= 2000 THEN '2000-1000'
        ELSE '>80000'
    END");
$stmt->execute();
$result = $stmt->get_result();
$salaryData = [];
while ($row = $result->fetch_assoc()) {
    $salaryData[] = $row;
}

// Get recent employees
$stmt = $con->prepare("SELECT 
    EmpCode,
    EmpName as name,
    Email,
    Department,
    Position,
    StartDate as hire_date,
    TIMESTAMPDIFF(YEAR, StartDate, CURDATE()) as experience
    FROM hrstaffprofile 
    WHERE Status = 'Active'
    ORDER BY StartDate DESC LIMIT 5");
$stmt->execute();
$result = $stmt->get_result();
$recentEmployees = [];
while ($row = $result->fetch_assoc()) {
    $recentEmployees[] = $row;
}


//Leave manangement
// Leave management
$sql = $con->prepare("SELECT COUNT(*) AS total FROM lmleaverequest");
$sql->execute();
$result = $sql->get_result();
$TotalLeaveRequest = $result->fetch_assoc()['total'];

//pending leave 
$sql = $con->prepare("SELECT Count(*) AS Pending FROM `lmleaverequest` Where Status='pending'");
$sql->execute();
$result = $sql->get_result();
$PendingLeave = $result->fetch_assoc()['Pending'];

//Approved leave 
$sql = $con->prepare("SELECT Count(*) AS approved FROM `lmleaverequest` Where Status='Approved'");
$sql->execute();
$result = $sql->get_result();
$ApprovedLeave = $result->fetch_assoc()['approved'];

//Rejected leave 
$sql = $con->prepare("SELECT Count(*) AS Rejected FROM `lmleaverequest` Where Status='Rejected'");
$sql->execute();
$result = $sql->get_result();
$RejectedLeave = $result->fetch_assoc()['Rejected'];
?>