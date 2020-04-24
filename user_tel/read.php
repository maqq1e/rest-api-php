<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../shared/utilities.php';
include_once '../objects/user_tel.php';

$database   = new Database();
$db         = $database->getConnection();

$user_tel = new UserTel($db);

// Set ID property for write and read
if(isset($_GET['id']))
{
    $user_tel->id = $_GET['id'];
}
else
{
    Util::sendMessage("Вы должны ввести id пользователя.");
    die();
}


$tels   = $user_tel->read();

if (!empty($tels))
{

    $user_tels_array = array();
    $user_tels_array['user_id'] = $user_tel->id;
    $user_tels_array['result']  = $tels;


    http_response_code(200);

    echo json_encode($user_tels_array);
}
else
{
    http_response_code(404);

    Util::sendMessage("Номера для данного пользователя не найдены.");
}
