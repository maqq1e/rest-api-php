<?php

class Util
{

    static function sendMessage($text) // Need realy only for multilanguage
    {
        echo json_encode(array("message" => $text), JSON_UNESCAPED_UNICODE);
        die();
    }

}