<?php
include_once '../src/db.php';
include_once '../src/utils.php';

session_start();
header("Access-Control-Allow-Methods: POST");

$data = getJsonInput();

if (!isset($data['email']) || !isset($data['senha'])) {
    sendJSON(["message" => "Dados incompletos."], 400);
}

$database = new Database();
$db = $database->getConnection();

$email = sanitize($data['email']);
$senha = $data['senha'];

$query = "SELECT id, nome, senha_hash, role FROM users WHERE email = :email LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":email", $email);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($senha, $row['senha_hash'])) {
        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['nome'] = $row['nome'];
        $_SESSION['role'] = $row['role'];

        sendJSON([
            "message" => "Login realizado com sucesso.",
            "user" => [
                "id" => $row['id'],
                "nome" => $row['nome'],
                "role" => $row['role']
            ]
        ]);
    } else {
        sendJSON(["message" => "Senha incorreta."], 401);
    }
} else {
    sendJSON(["message" => "Usuário não encontrado."], 401);
}
?>