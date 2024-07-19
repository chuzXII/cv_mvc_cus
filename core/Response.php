<?php
namespace Core;

class Response
{
    public static function json($data, $status = 200, array $headers = [])
    {

        http_response_code($status);

    
        header('Content-Type: application/json');
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }

        echo json_encode($data);

        exit;
    }
}
