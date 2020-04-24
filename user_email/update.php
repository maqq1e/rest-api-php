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

if(isset($data->id))
{
    // Email validation
    if(isset($data->email))
    {
        if( preg_match('/.+@.+\..+/i', $data->email))
        {
            $user_email->email      = $data->email;
        }
        else
        {
            Util::sendMessage("Укажите действительную почту.");
        }
    }
    else
    {
        $user_email->email = null;
    }

    // Get id of element (user_email) from user
    $user_email->id = $data->id;
    $user_email->is_main    = isset($data->is_main) ? $data->is_main : null;

    if ($user_email->update())
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
    Util::sendMessage("Невозможно обновить профиль пользователя. Заполните поле id!");
}

?>