<?php
session_start();
require 'conexao.php'; // Conexão com o banco de dados

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'responsavel') {
    header('Location: login.php');
    exit;
}

// Obter localização do responsável
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT location FROM guardians WHERE id = ?");
$stmt->execute([$userId]);
$responsavel = $stmt->fetch();

if ($responsavel) {
    $responsavelLocation = $responsavel['location'];
    list($latResponsavel, $lonResponsavel) = sscanf($responsavelLocation, "Lat: %f, Lon: %f");

    // Obter babás
    $stmt = $pdo->query("SELECT *, (6371 * acos(cos(radians($latResponsavel)) * cos(radians(SUBSTRING_INDEX(location, ',', 1))) * cos(radians(SUBSTRING_INDEX(location, ',', -1)) - radians($lonResponsavel)) + sin(radians($latResponsavel)) * sin(radians(SUBSTRING_INDEX(location, ',', 1))))) AS distance FROM babysitters HAVING distance < 10 ORDER BY distance");
    $babysitters = $stmt->fetchAll();
} else {
    $babysitters = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Babás Próximas</title>
</head>
<body>
    <h2>Babás Próximas</h2>
    <ul>
        <?php foreach ($babysitters as $baba): ?>
            <li>
                <h3><?php echo htmlspecialchars($baba['name']); ?></h3>
                <p>Valor por Hora: R$ <?php echo htmlspecialchars($baba['hourly_rate']); ?></p>
                <p>Experiência: <?php echo htmlspecialchars($baba['experience']); ?></p>
                <p>Localização: <?php echo htmlspecialchars($baba['location']); ?></p>
                <img src="uploads/<?php echo htmlspecialchars($baba['photo']); ?>" alt="Foto de <?php echo htmlspecialchars($baba['name']); ?>" width="100"><br>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
