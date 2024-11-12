<?php
// Conexão com o banco de dados
require_once 'conexao.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Função para calcular a distância entre duas coordenadas
function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
{
    $earthRadius = 6371; // Raio da Terra em km

    $dLat = deg2rad($latitudeTo - $latitudeFrom);
    $dLon = deg2rad($longitudeTo - $longitudeFrom);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) *
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c; // Retorna a distância em km
}

// Obter a localização do usuário logado
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT latitude, longitude, user_type FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$user = $query->get_result()->fetch_assoc();

$latitude = $user['latitude'];
$longitude = $user['longitude'];

// Buscar responsáveis ou babás próximos
$query = $conn->prepare("SELECT * FROM users WHERE id != ?"); // Busca todos, exceto o próprio usuário
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

$proximos = [];
while ($row = $result->fetch_assoc()) {
    $distancia = haversineGreatCircleDistance($latitude, $longitude, $row['latitude'], $row['longitude']);
    if ($distancia <= 10) { // Considerando uma distância de até 10 km
        $proximos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuários</title>
</head>
<body>
    <header>
        <h1>Usuários Próximos</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <?php if (count($proximos) > 0): ?>
            <ul>
                <?php foreach ($proximos as $usuario): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($usuario['name']); ?></strong><br>
                        <?php if ($usuario['user_type'] === 'baba'): ?>
                            <span>Babá</span>
                        <?php else: ?>
                            <span>Responsável</span>
                        <?php endif; ?>
                        <a href="mensagem.php?user_id=<?php echo $usuario['id']; ?>">Enviar Mensagem</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum usuário encontrado nas proximidades.</p>
        <?php endif; ?>
    </main>
</body>
</html>
