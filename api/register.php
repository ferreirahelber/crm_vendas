<?php
include_once '../src/db.php';
include_once '../src/utils.php';

header("Access-Control-Allow-Methods: POST");

$data = getJsonInput();

if (
    !isset($data['nome']) ||
    !isset($data['email']) ||
    !isset($data['senha'])
) {
    sendJSON(["message" => "Dados incompletos."], 400);
}

$database = new Database();
$db = $database->getConnection();

$nome = sanitize($data['nome']);
$email = sanitize($data['email']);
$senha = $data['senha'];
$senha_hash = password_hash($senha, PASSWORD_BCRYPT);
// Default role is 'normal'. 'admin' must be set manually in DB for security.
$role = 'normal';

// Check if email already exists
$checkQuery = "SELECT id FROM users WHERE email = :email LIMIT 1";
$checkStmt = $db->prepare($checkQuery);
$checkStmt->bindParam(":email", $email);
$checkStmt->execute();

if ($checkStmt->rowCount() > 0) {
    sendJSON(["message" => "O email já está em uso."], 400);
}

$query = "INSERT INTO users SET nome=:nome, email=:email, senha_hash=:senha_hash, role=:role";
$stmt = $db->prepare($query);

$stmt->bindParam(":nome", $nome);
$stmt->bindParam(":email", $email);
$stmt->bindParam(":senha_hash", $senha_hash);
$stmt->bindParam(":role", $role);

if ($stmt->execute()) {
    sendJSON(["message" => "Usuário registrado com sucesso."]);
} else {
    sendJSON(["message" => "Não foi possível registrar o usuário."], 503);
}
?>