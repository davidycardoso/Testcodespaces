<?php
// contratar_baba.php

require_once 'conexao.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'responsavel') {
    header("Location: login.php");
    exit();
}

$baba_id = $_GET['id'];
$query = $conn->prepare("SELECT * FROM babysitters WHERE id = ?");
$query->bind_param("i", $baba_id);
$query->execute();
$baba = $query->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lógica para salvar a contratação no banco de dados
    $responsavel_id = $_SESSION['user_id'];
    // Exemplo de inserção na tabela de contratações
    $query_insert = $conn->prepare("INSERT INTO contracts (guardian_id, babysitter_id) VALUES (?, ?)");
    $query_insert->bind_param("ii", $responsavel_id, $baba_id);
    if ($query_insert->execute()) {
        echo "Babá contratada com sucesso!";
    } else {
        echo "Erro ao contratar a babá.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contratar <?php echo htmlspecialchars($baba['name']); ?></title>
</head>
<body>
    <header>
        <h1>Contratar Babá</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <h2>Confirmar Contratação</h2>
        <p>Nome da Babá: <?php echo htmlspecialchars($baba['name']); ?></p>
        <p>Taxa Horária: R$ <?php echo htmlspecialchars($baba['hourly_rate']); ?></p>

        <form method="post">
            <button type="submit">Confirmar Contratação</button>
        </form>
    </main>
</body>
</html>
