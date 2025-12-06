<?php

function sendJSON($data, $status = 200)
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function getJsonInput()
{
    return json_decode(file_get_contents("php://input"), true);
}

function sanitize($data)
{
    return htmlspecialchars(strip_tags($data));
}
?>