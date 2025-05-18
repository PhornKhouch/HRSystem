<?php
include("../../../Config/conect.php");

// Initialize response array
$response = array();

// Get employee code
$empCode = isset($_POST['empCode']) ? $_POST['empCode'] : '';

if (empty($empCode)) {
    $response = array(
        'status' => 'error',
        'message' => 'Employee code is required'
    );
} else {
    try {
        // Get employee details
        $empQuery = "SELECT 
                        EmpCode,
                        E.EmpName as EmpName
                     FROM HrStaffProfile E
                     WHERE EmpCode = '$empCode'";
        
        $empResult = mysqli_query($con, $empQuery);
        $employee = mysqli_fetch_assoc($empResult);

        // Get family members
        $familyQuery = "SELECT 
                            RelationName,
                            RelationType,
                            Gender,
                            IsTax,
                            Remarks
                        FROM hrfamily
                        WHERE EmpCode = '$empCode'";
        
        $familyResult = mysqli_query($con, $familyQuery);
        
        $familyMembers = array();
        while ($row = mysqli_fetch_assoc($familyResult)) {
            $familyMembers[] = $row;
        }

        $response = array(
            'status' => 'success',
            'data' => array(
                'employee' => $employee,
                'familyMembers' => $familyMembers
            )
        );
    } catch (Exception $e) {
        $response = array(
            'status' => 'error',
            'message' => 'An error occurred: ' . $e->getMessage()
        );
    }
}

// Close connection
mysqli_close($con);

// Send response
header('Content-Type: application/json');
echo json_encode($response);