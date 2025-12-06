<?php
include_once '../src/db.php';
include_once '../src/utils.php';
include_once '../src/auth.php';

checkApiAuth();
header("Access-Control-Allow-Methods: POST");

$data = getJsonInput();

if (!isset($data['id'])) {
    sendJSON(["message" => "ID não fornecido."], 400);
}

$database = new Database();
$db = $database->getConnection();
$user_id = $_SESSION['user_id'];
$id = $data['id'];

// Check ownership
$checkQuery = "SELECT id FROM receitas WHERE id = :id AND user_id = :user_id LIMIT 1";
$checkStmt = $db->prepare($checkQuery);
$checkStmt->bindParam(":id", $id);
$checkStmt->bindParam(":user_id", $user_id);
$checkStmt->execute();

if ($checkStmt->rowCount() == 0) {
    sendJSON(["message" => "Receita não encontrada ou acesso negado."], 404);
}

$query = "DELETE FROM receitas WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(":id", $id);

if ($stmt->execute()) {
    sendJSON(["message" => "Receita removida com sucesso."]);
} else {
    sendJSON(["message" => "Erro ao remover receita."], 503);
}
?>