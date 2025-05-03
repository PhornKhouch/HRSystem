<?php
//send to telegram
function sendTelegramMessage($message,$botToken,$groupID) {
    $botToken = $botToken; // Telegram bot token
    $chatId = $groupID;     //  group chat ID
    
    $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
    
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    return $result;
}



function GetMessageForLeave($name, $leaveType, $fromDate, $toDate, $status) {

    return "<b>----Leave Request----</b>\n\n" .
           "<b>Employee Name:</b> {$name}\n" .
           "<b>Leave Type:</b> {$leaveType}\n" .
           "<b>From Date:</b> {$fromDate}\n" .
           "<b>To Date:</b> {$toDate}\n" .
           "<b>Status:</b> {$status}\n";
}

