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

// Validation for middle name is off cause human can hasn't middle name
if (!empty($data->name) && !empty($data->surname) /* && !empty($data->middle) */)
{
    $user->name     = $data->name;
    $user->surname  = $data->surname;
    $user->middle   = $data->middle;
    $user->created  = date('Y-m-d H:i:s');

    if($user->create())
    {
        http_response_code(201);

        Util::sendMessage("Профиль был создан.");
    }
    else
    {
        http_response_code(503);

        Util::sendMessage("Невозможно создать профиль пользователя.");
    }
}
else
{
    http_response_code(400);

    Util::sendMessage("Невозможно создать профиль пользователя. Данные неполные.");
}
?>