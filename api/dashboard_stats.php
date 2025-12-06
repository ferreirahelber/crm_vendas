<?php
include_once '../src/db.php';
include_once '../src/utils.php';
include_once '../src/auth.php';

checkApiAuth();
header("Access-Control-Allow-Methods: GET");

$database = new Database();
$db = $database->getConnection();
$user_id = $_SESSION['user_id'];

// Totais
$queryReceitas = "SELECT SUM(valor) as total FROM receitas WHERE user_id = :user_id";
$stmt = $db->prepare($queryReceitas);
$stmt->bindParam(":user_id", $user_id);
$stmt->execute();
$totalReceitas = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$queryDespesas = "SELECT SUM(valor) as total FROM despesas WHERE user_id = :user_id";
$stmt = $db->prepare($queryDespesas);
$stmt->bindParam(":user_id", $user_id);
$stmt->execute();
$totalDespesas = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Gráfico (Últimos 6 meses)
$chartData = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $monthLabel = date('M', strtotime("-$i months")); // Nome do mês curto

    // Receita do mês
    $qR = "SELECT SUM(valor) as total FROM receitas WHERE user_id = :user_id AND DATE_FORMAT(data, '%Y-%m') = :month";
    $sR = $db->prepare($qR);
    $sR->bindParam(":user_id", $user_id);
    $sR->bindParam(":month", $month);
    $sR->execute();
    $valR = $sR->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // Despesa do mês
    $qD = "SELECT SUM(valor) as total FROM despesas WHERE user_id = :user_id AND DATE_FORMAT(data, '%Y-%m') = :month";
    $sD = $db->prepare($qD);
    $sD->bindParam(":user_id", $user_id);
    $sD->bindParam(":month", $month);
    $sD->execute();
    $valD = $sD->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    $chartData['labels'][] = $monthLabel;
    $chartData['receitas'][] = $valR;
    $chartData['despesas'][] = $valD;
}

// Últimos lançamentos (Mix de receitas e despesas)
// Union para pegar os últimos 5 de ambos
$queryRecent = "
(SELECT id, descricao, valor, categoria, data, 'receita' as tipo FROM receitas WHERE user_id = :uid1)
UNION ALL
(SELECT id, descricao, valor, categoria, data, 'despesa' as tipo FROM despesas WHERE user_id = :uid2)
ORDER BY data DESC, id DESC LIMIT 5
";

$stmtRecent = $db->prepare($queryRecent);
$stmtRecent->bindParam(":uid1", $user_id);
$stmtRecent->bindParam(":uid2", $user_id);
$stmtRecent->execute();
$recent = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);

sendJSON([
    "totais" => [
        "receitas" => $totalReceitas,
        "despesas" => $totalDespesas,
        "saldo" => $totalReceitas - $totalDespesas
    ],
    "chart" => $chartData,
    "recentes" => $recent
]);
?>