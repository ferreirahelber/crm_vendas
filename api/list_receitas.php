<?php
include_once '../src/db.php';
include_once '../src/utils.php';
include_once '../src/auth.php';

checkApiAuth();
header("Access-Control-Allow-Methods: GET");

$database = new Database();
$db = $database->getConnection();
$user_id = $_SESSION['user_id'];

$start_date = isset($_GET['start_date']) ? sanitize($_GET['start_date']) : null;
$end_date = isset($_GET['end_date']) ? sanitize($_GET['end_date']) : null;
$categoria = isset($_GET['categoria']) ? sanitize($_GET['categoria']) : null;

$query = "SELECT * FROM receitas WHERE user_id = :user_id";

if ($start_date) {
    $query .= " AND data >= :start_date";
}
if ($end_date) {
    $query .= " AND data <= :end_date";
}
if ($categoria) {
    $query .= " AND categoria = :categoria";
}

$query .= " ORDER BY data DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(":user_id", $user_id);

if ($start_date) {
    $stmt->bindParam(":start_date", $start_date);
}
if ($end_date) {
    $stmt->bindParam(":end_date", $end_date);
}
if ($categoria) {
    $stmt->bindParam(":categoria", $categoria);
}

$stmt->execute();
$receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendJSON($receitas);
?>