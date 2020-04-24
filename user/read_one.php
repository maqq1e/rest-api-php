<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

include_once '../config/database.php';
include_once '../shared/utilities.php';
include_once '../objects/user.php';

$database = new Database();

$db = $database->getConnection();

$user = new User($db);

// Set ID property for write and read
if(isset($_GET['id']))
{
    $user->id = $_GET['id'];
}
else
{
    Util::sendMessage("Вы должны ввести id пользователя.");
    die();
}

$user->readOne();

if ($user->name != null)
{
    $user_array = array(
        "name"      => $user->name,
        "surname"   => $user->surname,
        "middle"    => $user->middle,
        "tel_list"  => $user->tel_list
    );

    http_response_code(200);

    echo json_encode($user_array);
}
else
{
    http_response_code(404);

    Util::sendMessage("Профиль не существует.");
}
?>