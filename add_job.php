<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'baba') {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'babybuddy';
$username = 'root'; // Altere para o seu usuário do MySQL
$password = 'root'; // Altere para a sua senha do MySQL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $babysitterId = $_SESSION['user_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $hourlyRate = $_POST['hourly_rate'];
        $location = $_POST['location'];

        $stmt = $pdo->prepare("INSERT INTO jobs (babysitter_id, title, description, hourly_rate, location) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$babysitterId, $title, $description, $hourlyRate, $location]);

        echo "Oportunidade de trabalho adicionada com sucesso!";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Oportunidade de Trabalho</title>
</head>
<body>
    <h1>Adicionar Oportunidade de Trabalho</h1>
    <form method="POST" action="">
        <label for="title">Título:</label>
        <input type="text" name="title" required>

        <label for="description">Descrição:</label>
        <textarea name="description" required></textarea>

        <label for="hourly_rate">Valor por Hora:</label>
        <input type="number" name="hourly_rate" step="0.01" required>

        <label for="location">Localização:</label>
        <input type="text" name="location" required>

        <button type="submit">Adicionar</button>
    </form>
    <a href="dashboard_baba.php">Voltar ao Dashboard</a>
</body>
</html>
