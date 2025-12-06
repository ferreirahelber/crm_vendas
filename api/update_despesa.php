<?php
include_once '../src/db.php';
include_once '../src/utils.php';
include_once '../src/auth.php';

checkApiAuth();
header("Access-Control-Allow-Methods: POST");

$data = getJsonInput();

if (
    !isset($data['id']) ||
    !isset($data['descricao']) ||
    !isset($data['valor']) ||
    !isset($data['data'])
) {
    sendJSON(["message" => "Dados incompletos."], 400);
}

$database = new Database();
$db = $database->getConnection();
$user_id = $_SESSION['user_id'];

$id = $data['id'];
$descricao = sanitize($data['descricao']);
$valor = $data['valor'];
$categoria = isset($data['categoria']) ? sanitize($data['categoria']) : 'Geral';
$data_lancamento = $data['data'];

// Verifica ownership
$check = $db->prepare("SELECT id FROM despesas WHERE id=:id AND user_id=:user_id");
$check->bindParam(":id", $id);
$check->bindParam(":user_id", $user_id);
$check->execute();
if ($check->rowCount() == 0)
    sendJSON(["message" => "Não encontrado."], 404);

$query = "UPDATE despesas SET descricao=:descricao, valor=:valor, categoria=:categoria, data=:data WHERE id=:id";
$stmt = $db->prepare($query);

$stmt->bindParam(":descricao", $descricao);
$stmt->bindParam(":valor", $valor);
$stmt->bindParam(":categoria", $categoria);
$stmt->bindParam(":data", $data_lancamento);
$stmt->bindParam(":id", $id);

if ($stmt->execute()) {
    sendJSON(["message" => "Despesa atualizada."]);
} else {
    sendJSON(["message" => "Erro ao atualizar."], 503);
}
?>