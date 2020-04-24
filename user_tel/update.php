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

if(isset($data->id))
{
    // Get id of element (user_tel) from user
    $user_tel->id = $data->id;

    if(isset($data->number))
    {
        // Phone validation
        if(preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/', $data->number))
        {
            $user_tel->number   = $data->number;
        }
        else
        {
            Util::sendMessage("Укажите действительный номер телефона.");
        }
    }
    else
    {
        $user_tel->number = null;
    }

    $user_tel->type     = isset($data->type) ? $data->type : null;
    $user_tel->is_main  = isset($data->is_main) ? $data->is_main : null;

    if ($user_tel->update())
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
?>