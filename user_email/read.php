<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../shared/utilities.php';
include_once '../objects/user_email.php';

$database   = new Database();
$db         = $database->getConnection();

$user_email = new UserEmail($db);

// Set ID property for write and read
if(isset($_GET['id']))
{
    $user_email->id = $_GET['id'];
}
else
{
    http_response_code(400);
    
    Util::sendMessage("Вы должны ввести id пользователя.");
}


$emails = $user_email->read();

if (!empty($emails))
{

    $user_emails_array = array();
    $user_emails_array['user_id'] = $user_email->id;
    $user_emails_array['result']  = $emails;


    http_response_code(200);

    echo json_encode($user_emails_array);
}
else
{
    http_response_code(404);

    Util::sendMessage("Номера для данного пользователя не найдены.");
}
