<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../shared/utilities.php';
include_once '../objects/user_email.php';

$database = new Database();

$db = $database->getConnection();

$user_email = new UserEmail($db);

$data = json_decode(file_get_contents("php://input")); // Need for "crude" POST data

if (!empty($data->user_id) && !empty($data->email))
{
    // Email validation
    if(preg_match('/.+@.+\..+/i', $data->email))
    {
        $user_email->email      = $data->email;
    }
    else
    {
        Util::sendMessage("Укажите действительную почту.");
    }

    $user_email->user_id    = $data->user_id;
    $user_email->is_main    = isset($data->is_main) ? 1 : 0;
    $user_email->created    = date('Y-m-d H:i:s');

    if($user_email->create())
    {
        http_response_code(201);

        Util::sendMessage("Почта была добавлена.");
    }
    else
    {
        http_response_code(503);

        Util::sendMessage("Невозможно добавить почту.");
    }
}
else
{
    http_response_code(400);

    Util::sendMessage("Невозможно добавить почту. Данные неполные.");
}
?>