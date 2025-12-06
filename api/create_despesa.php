<?php
include_once '../src/db.php';
include_once '../src/utils.php';
include_once '../src/auth.php';

checkApiAuth();
header("Access-Control-Allow-Methods: POST");

$data = getJsonInput();

if (
    !isset($data['descricao']) ||
    !isset($data['valor']) ||
    !isset($data['data'])
) {
    sendJSON(["message" => "Dados incompletos."], 400);
}

$database = new Database();
$db = $database->getConnection();

$user_id = $_SESSION['user_id'];
$descricao = sanitize($data['descricao']);
$valor = $data['valor'];
$categoria = isset($data['categoria']) ? sanitize($data['categoria']) : 'Geral';
$data_lancamento = $data['data'];

$query = "INSERT INTO despesas SET user_id=:user_id, descricao=:descricao, valor=:valor, categoria=:categoria, data=:data";
$stmt = $db->prepare($query);

$stmt->bindParam(":user_id", $user_id);
$stmt->bindParam(":descricao", $descricao);
$stmt->bindParam(":valor", $valor);
$stmt->bindParam(":categoria", $categoria);
$stmt->bindParam(":data", $data_lancamento);

if ($stmt->execute()) {
    sendJSON(["message" => "Despesa criada com sucesso."]);
} else {
    sendJSON(["message" => "Erro ao criar despesa."], 503);
}
?>