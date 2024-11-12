<?php
// search_babysitters.php
include 'conexao.php'; // Inclua a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location = $_POST['location'];
    $sql = "SELECT * FROM babysitters WHERE location LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchLocation = "%" . $location . "%";
    $stmt->bind_param("s", $searchLocation);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <title>Buscar Babás</title>
</head>
<body>
    <header>
        <h1>Buscar Babás</h1>
        <a href="dashboard_responsavel.php" class="button">Voltar ao Dashboard</a>
    </header>
    <main>
        <form method="POST">
            <label for="location">Digite sua localização:</label>
            <input type="text" id="location" name="location" required>
            <button type="submit" class="button">Buscar</button>
        </form>

        <?php if (isset($result) && $result->num_rows > 0): ?>
            <h2>Babás Disponíveis</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Taxa Horária</th>
                        <th>Experiência</th>
                        <th>Localização</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($babysitter = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($babysitter['name']); ?></td>
                            <td>R$ <?php echo htmlspecialchars($babysitter['hourly_rate']); ?></td>
                            <td><?php echo htmlspecialchars($babysitter['experience']); ?></td>
                            <td><?php echo htmlspecialchars($babysitter['location']); ?></td>
                            <td><a href="view_babysitter.php?id=<?php echo $babysitter['id']; ?>" class="button">Ver Detalhes</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif (isset($result)): ?>
            <p>Nenhuma babá encontrada para a localização informada.</p>
        <?php endif; ?>
    </main>
</body>
</html>
