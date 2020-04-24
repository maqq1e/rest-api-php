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

// Get id of element (user_email) from user
$user_email->id = $data->id;

if ($user_email->delete())
{
    http_response_code(200);

    Util::sendMessage("Номер телефона был удалён.");
}
else
{
    http_response_code(503);

    Util::sendMessage("Не удалось удалить номер телефона.");
}
?>