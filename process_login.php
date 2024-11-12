<?php
session_start();
// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'babybuddy';
$username = 'root'; // Altere para o seu usuário do MySQL
$password = 'root'; // Altere para a sua senha do MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Verifica se o usuário é uma babá
        $stmt = $pdo->prepare("SELECT * FROM babysitters WHERE email = ?");
        $stmt->execute([$email]);
        $babysitter = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se a babá foi encontrada e a senha está correta
        if ($babysitter && password_verify($password, $babysitter['password'])) {
            $_SESSION['user_id'] = $babysitter['id'];
            $_SESSION['user_type'] = 'baba'; // Define o tipo de usuário
            header("Location: dashboard_baba.php"); // Redireciona para o dashboard da babá
            exit();
        }

        // Verifica se o usuário é um responsável
        $stmt = $pdo->prepare("SELECT * FROM guardians WHERE email = ?");
        $stmt->execute([$email]);
        $guardian = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o responsável foi encontrado e a senha está correta
        if ($guardian && password_verify($password, $guardian['password'])) {
            $_SESSION['user_id'] = $guardian['id'];
            $_SESSION['user_type'] = 'responsavel'; // Define o tipo de usuário
            header("Location: dashboard_responsavel.php"); // Redireciona para o dashboard do responsável
            exit();
        }

        // Se as credenciais não forem válidas
        echo "E-mail ou senha inválidos.";
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
