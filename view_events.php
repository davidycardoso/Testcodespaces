<?php
require_once 'conexao.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Função para buscar os eventos
function fetch_events($conn, $user_id) {
    $query = "SELECT * FROM events WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

$events = fetch_events($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Criados</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <header>
        <h1>Meus Eventos</h1>
        <a href="logout.php" class="logout">Logout</a>
    </header>
    
    <main>
        <section id="events">
            <h2>Eventos Criados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Início</th>
                        <th>Fim</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($event = $events->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo htmlspecialchars($event['start']); ?></td>
                            <td><?php echo htmlspecialchars($event['end']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="<?php echo ($user_type === 'baba') ? 'dashboard_baba.php' : 'dashboard_responsavel.php'; ?>" class="button">Voltar para o Dashboard</a> <!-- Botão para voltar -->
        </section>
    </main>

    <footer>
        <p>&copy; 2024 BabyBuddy</p>
    </footer>
</body>
</html>
