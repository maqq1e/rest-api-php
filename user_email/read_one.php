<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

include_once '../config/database.php';
include_once '../shared/utilities.php';
include_once '../objects/user_email.php';

$database = new Database();

$db = $database->getConnection();

$user_email = new UserEmail($db);

// Set ID property for write and read
if(isset($_GET['id']))
{
    $user_email->id = $_GET['id'];
}
else
{
    Util::sendMessage("Вы должны ввести id почты.");
    die();
}

$user_email->readOne();

if ($user_email->email != null)
{

    $user_email_array = array(
        "user_id"   => $user_email->user_id,
        "email"     => $user_email->email,
        "is_main"   => $user_email->is_main
    );

    http_response_code(200);

    echo json_encode($user_email_array);
}
else
{
    http_response_code(404);

    Util::sendMessage("Такой почты не существует.");
}
?>