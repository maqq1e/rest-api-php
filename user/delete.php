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

// Get id of element (user) from user
$user->id = $data->id;

if ($user->delete())
{
    http_response_code(200);

    Util::sendMessage("Профиль был удалён.");
}
else
{
    http_response_code(503);

    Util::sendMessage("Не удалось удалить профиль.");
}
?>