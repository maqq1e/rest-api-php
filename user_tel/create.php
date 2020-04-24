<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../shared/utilities.php';
include_once '../objects/user_tel.php';

$database = new Database();

$db = $database->getConnection();

$user_tel = new UserTel($db);

$data = json_decode(file_get_contents("php://input")); // Need for "crude" POST data

if (!empty($data->user_id) && !empty($data->number))
{
    // Phone validation
    if(preg_match('/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/', $data->number))
    {
        $user_tel->number   = $data->number;
    }
    else
    {
        Util::sendMessage("Укажите действительный номер телефона.");
    }

    $user_tel->user_id  = $data->user_id;

    // Probably in frontend user will select something in <select> where option has id from 0 to 2
    $user_tel->type     = (0 < $data->type && $data->type < 3) ? $data->type : 0; 
    $user_tel->is_main  = isset($data->is_main) ? 1 : 0;
    $user_tel->created  = date('Y-m-d H:i:s');

    if($user_tel->create())
    {
        http_response_code(201);

        Util::sendMessage("Номер телефона был добавлен.");
    }
    else
    {
        http_response_code(503);

        Util::sendMessage("Невозможно добавить номер телефона.");
    }
}
else
{
    http_response_code(400);

    Util::sendMessage("Невозможно добавить номер телефона. Данные неполные.");
}
?>