<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../shared/utilities.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input")); // Need for "crude" POST data

if(isset($data->id))
{
    // Get id of element (user) from user
    $user->id = $data->id;

    $user->name     = isset($data->name) ? $data->name : null;
    $user->surname  = isset($data->surname) ? $data->surname : null;
    $user->middle   = isset($data->middle) ? $data->middle : null;

    if ($user->update())
    {
        http_response_code(200);

        Util::sendMessage("Профиль был обновлён.");
    }
    else
    {
        http_response_code(503);

        Util::sendMessage("Невозможно обновить профиль пользователя.");
    }
}
else
{
    http_response_code(400);

    Util::sendMessage("Невозможно обновить профиль пользователя. Заполните поле id!");
}
?>