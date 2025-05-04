<?php
include("../../Config/conect.php");

//delete leave type
if (isset($_POST['type']) && $_POST['type'] == "LeaveType") {
    $code = $_POST['code'];
    
    // Prepare the SQL with proper escaping to prevent SQL injection
    $code = $con->real_escape_string($code);
    
    $SQL = "DELETE FROM lmleavetype WHERE Code = '$code'";
    
    $result = $con->query($SQL);
    
    if($result) {
        echo "Record deleted successfully";
    } else {
        http_response_code(400);
        echo "Error: " . $con->error;
    }
}

// delete public holiday
else if (isset($_POST['type']) && $_POST['type'] == "PublicHoliday") {
    $id = $_POST['id'];
    
    // Prepare the SQL with proper escaping to prevent SQL injection
    $id = $con->real_escape_string($id);
    
    $SQL = "DELETE FROM public_holidays WHERE id = '$id'";
    
    $result = $con->query($SQL);
    
    if($result) {
        echo "Record deleted successfully";
    } else {
        http_response_code(400);
        echo "Error: " . $con->error;
    }
}

// delete telegram configuration
else if (isset($_POST['type']) && $_POST['type'] == "TelegramConfig") {
    $id = $_POST['id'];
    
    // Prepare the SQL with proper escaping to prevent SQL injection
    $id = $con->real_escape_string($id);
    
    $SQL = "DELETE FROM sytelegram_config WHERE id = '$id'";
    
    $result = $con->query($SQL);
    
    if($result) {
        echo json_encode(['status' => 'success', 'message' => 'Telegram configuration deleted successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $con->error]);
    }
}

?>
