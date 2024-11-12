<?php
// view_babysitter.php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM babysitters WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <title><?php echo htmlspecialchars($result['name']); ?> - Detalhes</title>
</head>
<body>
    <header>
        <h1>Detalhes da Babá</h1>
        <a href="search_babysitters.php" class="button">Voltar à Busca</a>
    </header>
    <main>
        <?php if ($result): ?>
            <h2><?php echo htmlspecialchars($result['name']); ?></h2>
            <img src="<?php echo htmlspecialchars($result['photo']); ?>" alt="Foto da Babá" class="profile-photo">
            <p>Taxa horária: R$ <?php echo htmlspecialchars($result['hourly_rate']); ?></p>
            <p>Experiência: <?php echo htmlspecialchars($result['experience']); ?></p>
            <p>Localização: <?php echo htmlspecialchars($result['location']); ?></p>
            <a href="contract_babysitter.php?id=<?php echo $result['id']; ?>" class="button">Contratar</a>
        <?php else: ?>
            <p>Babá não encontrada.</p>
        <?php endif; ?>
    </main>
</body>
</html>
