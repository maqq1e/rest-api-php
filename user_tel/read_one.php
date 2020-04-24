<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

include_once '../config/database.php';
include_once '../shared/utilities.php';
include_once '../objects/user_tel.php';

$database = new Database();

$db = $database->getConnection();

$user_tel = new UserTel($db);

// Set ID property for write and read
if(isset($_GET['id']))
{
    $user_tel->id = $_GET['id'];
}
else
{
    Util::sendMessage("Вы должны ввести id номера телефона.");
    die();
}

$user_tel->readOne();

if ($user_tel->number != null)
{

    $user_tel_array = array(
        "id"        => $user_tel->id,
        "number"    => $user_tel->number,
        "type"      => $user_tel->type,
        "is_main"   => $user_tel->is_main
    );

    http_response_code(200);

    echo json_encode($user_tel_array);
}
else
{
    http_response_code(404);

    Util::sendMessage("Номера телефона не существует.");
}
?>