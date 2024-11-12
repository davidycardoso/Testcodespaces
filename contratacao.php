<?php
// Conexão com o banco de dados
require_once 'conexao.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'baba') {
    header("Location: login.php");
    exit();
}

// Obter a localização da babá usando a API de Geolocalização
$latitude = '';
$longitude = '';

if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
}

// Definir a distância máxima para encontrar empregos (em km)
$max_distance = 10;

// Função para calcular a distância entre dois pontos
function distance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371; // Raio da Terra em km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earth_radius * $c; // Distância em km
}

// Obter empregos disponíveis próximos
$jobs = [];
if ($latitude && $longitude) {
    $query = $conn->prepare("SELECT * FROM jobs");
    $query->execute();
    $result = $query->get_result();

    while ($job = $result->fetch_assoc()) {
        $job_distance = distance($latitude, $longitude, $job['latitude'], $job['longitude']);
        if ($job_distance <= $max_distance) {
            $jobs[] = $job;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contratação - Empregos Disponíveis</title>
    <link rel="stylesheet" href="css/contratacao.css">
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocalização não é suportada por este navegador.");
            }
        }

        function showPosition(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            document.getElementById('location-form').submit();
        }
    </script>
</head>
<body onload="getLocation()">
    <header>
        <h1>Empregos Disponíveis Próximos a Você</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <form id="location-form" method="POST" style="display: none;">
            <input type="hidden" id="latitude" name="latitude" value="">
            <input type="hidden" id="longitude" name="longitude" value="">
        </form>

        <section>
            <h2>Empregos Encontrados</h2>
            <?php if (empty($jobs)): ?>
                <p>Nenhum emprego encontrado nas proximidades.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($jobs as $job): ?>
                        <li>
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <p><?php echo htmlspecialchars($job['description']); ?></p>
                            <p>Taxa horária: R$ <?php echo htmlspecialchars($job['hourly_rate']); ?></p>
                            <p>Data: <?php echo htmlspecialchars($job['date']); ?></p>
                            <form method="POST" action="aplicar.php">
                                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                <button type="submit">Aplicar para o Emprego</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 BabyBuddy</p>
    </footer>
</body>
</html>
