<?php
include_once '../src/db.php';
include_once '../src/utils.php';
include_once '../src/auth.php';

checkApiAuth();

if (!isAdmin()) {
    sendJSON(["message" => "Acesso negado. Apenas administradores."], 403);
}

header("Access-Control-Allow-Methods: GET");

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, nome, email, role, created_at FROM users ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendJSON($users);
?>