<?php
session_start();

function checkAuth()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}

function checkApiAuth()
{
    if (!isset($_SESSION['user_id'])) {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function logout()
{
    session_destroy();
    header("Location: index.php");
    exit;
}
?>