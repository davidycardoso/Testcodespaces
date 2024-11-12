<?php
// contratar.php

require_once 'conexao.php';
session_start();

// Verifique se o usuário é um responsável
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'responsavel') {
    header("Location: login.php");
    exit();
}

// Obter todas as babás disponíveis
$query = $conn->query("SELECT * FROM babysitters");
$babysitters = $query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contratar Babá</title>
    <link rel="stylesheet" href="css/contratar.css"> <!-- Link para o CSS -->
</head>
<body>
    <header>
        <h1>Babás Disponíveis</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <h2>Escolha uma Babá</h2>
        <ul>
            <?php foreach ($babysitters as $baba): ?>
                <li>
                    <img src="<?php echo htmlspecialchars($baba['photo']); ?>" alt="Foto da Babá" width="100">
                    <p>Nome: <?php echo htmlspecialchars($baba['name']); ?></p>
                    <p>Taxa Horária: R$ <?php echo htmlspecialchars($baba['hourly_rate']); ?></p>
                    <a href="contratar_baba.php?id=<?php echo $baba['id']; ?>">Contratar</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>
