<?php
include("../../Config/conect.php");
if (isset($_POST['type'])) {
    if ($_POST['type'] == "LeaveType") {
        $code = $_POST['code'];
        $leaveType = $_POST['leaveType'];
        
        // Handle checkbox values - convert to 1/0 for database
        $isded = ($_POST['isded'] == 1 || $_POST['isded'] === 'on') ? 1 : 0;
        $isOverBalance = ($_POST['isOverBalance'] == 1 || $_POST['isOverBalance'] === 'on') ? 1 : 0;
        $isProbation = ($_POST['isProbation'] == 1 || $_POST['isProbation'] === 'on') ? 1 : 0;

        // Prepare the SQL with proper escaping to prevent SQL injection
        $code = $con->real_escape_string($code);
        $leaveType = $con->real_escape_string($leaveType);
        
        $SQL = "UPDATE lmleavetype 
                SET LeaveType = '$leaveType', 
                    IsDeduct = $isded, 
                    IsOverBalance = $isOverBalance, 
                    IsProbation = $isProbation 
                WHERE Code = '$code'";
                
        $result = $con->query($SQL);
        
        if($result) {
            echo "Record updated successfully";
        } else {
            http_response_code(400);
            echo "Error: " . $con->error;
        }
    } 
    elseif ($_POST['type'] == "PublicHoliday") {
        $id = $_POST['id'];
        $holiday_name = $_POST['holiday_name'];
        $holiday_date = $_POST['holiday_date'];
        $description = $_POST['description'];

        // Prepare the SQL with proper escaping to prevent SQL injection
        $id = $con->real_escape_string($id);
        $holiday_name = $con->real_escape_string($holiday_name);
        $holiday_date = $con->real_escape_string($holiday_date);
        $description = $con->real_escape_string($description);
        
        $SQL = "UPDATE public_holidays 
                SET holiday_name = '$holiday_name', 
                    holiday_date = '$holiday_date', 
                    description = '$description',
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = '$id'";
                
        $result = $con->query($SQL);
        
        if($result) {
            echo json_encode(['status' => 'success', 'message' => 'Public holiday updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $con->error]);
        }
    }
    elseif ($_POST['type'] == "TelegramConfig") {
        $id = $_POST['id'];
        $chat_name = $_POST['chat_name'];
        $chat_id = $_POST['chat_id'];
        $bot_token = $_POST['bot_token'];
        $description = $_POST['description'];
        $status = $_POST['status'];
        
        // Prepare the SQL with proper escaping to prevent SQL injection
        $id = $con->real_escape_string($id);
        $chat_name = $con->real_escape_string($chat_name);
        $chat_id = $con->real_escape_string($chat_id);
        $bot_token = $con->real_escape_string($bot_token);
        $description = $con->real_escape_string($description);
        
        $SQL = "UPDATE sytelegram_config 
                SET Chat_name = '$chat_name',
                    Chat_id = '$chat_id',
                    Bot_token = '$bot_token',
                    Description = '$description',
                    Status = $status,
                    UpdatedAt = CURRENT_TIMESTAMP
                WHERE id = '$id'";
                
        $result = $con->query($SQL);
        
        if($result) {
            echo json_encode(['status' => 'success', 'message' => 'Telegram configuration updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $con->error]);
        }
    }
} else {
    http_response_code(400);
    echo "Error: Type parameter is missing";
}
?>
