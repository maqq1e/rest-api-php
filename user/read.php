<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../shared/utilities.php';
include_once '../objects/user.php';

$database   = new Database();
$db         = $database->getConnection();

$user = new User($db);

$stmt   = $user->read();
$num    = $user->getCount();

if ($num > 0)
{
    $users_array                = array();
    $users_array['users_count'] = $num;
    $users_array["result"]     = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);

        $user_item = array(
            "id"            => $id,
            "name"          => $name,
            "surname"       => $surname,
            "middle"        => $middle,
            "tel_list"      => $user->getTelQuery($id),
            "email_list"    => $user->getEmailList($id),
        );

        array_push($users_array["result"], $user_item);
    }

    http_response_code(200);

    echo json_encode($users_array);
}
else
{

    http_response_code(404);

    Util::sendMessage("Пользователи не найдены.");
}
