<?php
session_start();
require 'conexao.php'; // Inclua sua conexão com o banco de dados

// Verifique se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Verifique se o parâmetro 'id' está definido
if (!isset($_GET['id'])) {
    echo "ID não fornecido.";
    exit;
}

$targetId = intval($_GET['id']); // ID da babá ou responsável

// Verifique se o ID é válido
if ($targetId <= 0) {
    echo "ID inválido.";
    exit;
}

// Carregar avaliações da babá ou responsável
$isBabysitter = $_SESSION['user_type'] === 'babysitter';
$isGuardian = $_SESSION['user_type'] === 'guardian';

$targetType = $isBabysitter ? 'guardian_id' : 'babysitter_id';

$query = $conn->prepare("SELECT r.rating, r.comment, r.created_at, u.name FROM reviews r JOIN " . ($isBabysitter ? 'guardians' : 'babysitters') . " u ON r." . $targetType . " = u.id WHERE r." . ($isBabysitter ? 'babysitter_id' : 'guardian_id') . " = ?");
$query->bind_param("i", $targetId);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Avaliações</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h1>Avaliações</h1>
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($row['name']); ?></strong> (Nota: <?php echo $row['rating']; ?>)
                    <p><?php echo htmlspecialchars($row['comment']); ?></p>
                    <small><?php echo $row['created_at']; ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Nenhuma avaliação encontrada.</p>
    <?php endif; ?>
</body>
</html>
