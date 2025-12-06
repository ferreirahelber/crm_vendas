<?php
include_once '../src/db.php';
include_once '../src/utils.php';
include_once '../src/auth.php';

checkApiAuth();

if (!isAdmin()) {
    sendJSON(["message" => "Acesso negado. Apenas administradores."], 403);
}

header("Access-Control-Allow-Methods: POST");

$data = getJsonInput();

if (!isset($data['id'])) {
    sendJSON(["message" => "ID não fornecido."], 400);
}

$database = new Database();
$db = $database->getConnection();
$id = $data['id'];

// Prevent deleting self (optional but good practice)
if ($id == $_SESSION['user_id']) {
    sendJSON(["message" => "Não é possível excluir o próprio usuário."], 400);
}

$query = "DELETE FROM users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(":id", $id);

if ($stmt->execute()) {
    sendJSON(["message" => "Usuário removido com sucesso."]);
} else {
    sendJSON(["message" => "Erro ao remover usuário."], 503);
}
?>