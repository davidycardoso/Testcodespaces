<?php
session_start();
require 'conexao.php'; // Arquivo com a conexão ao banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST['userType'];
    $email = ($userType === 'baba') ? $_POST['babaEmail'] : $_POST['responsavelEmail'];

    // Verificar se o email já existe na tabela de babysitters
    $stmt = $conn->prepare("SELECT * FROM babysitters WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Se o e-mail já existe na tabela de babysitters
    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = 'Este e-mail já está registrado como babá. Por favor, faça login ou use outro e-mail.';
        header('Location: register.php');
        exit;
    }

    // Verificar se o email já existe na tabela de guardians (responsáveis)
    $stmt = $conn->prepare("SELECT * FROM guardians WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Se o e-mail já existe na tabela de guardians
    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = 'Este e-mail já está registrado como responsável. Por favor, faça login ou use outro e-mail.';
        header('Location: register.php');
        exit;
    }

    // Para a babá
    if ($userType === 'baba') {
        $name = $_POST['babaName'];
        $password = password_hash($_POST['babaPassword'], PASSWORD_DEFAULT);
        $photo = $_FILES['babaPhoto']['name'];
        $hourlyRate = $_POST['babaRate'];
        $experience = $_POST['babaExperience'];
        $location = $_POST['location']; // Supondo que aqui já esteja no formato "Lat: x, Lon: y"

        // Salvar dados da babá no banco
        $stmt = $conn->prepare("INSERT INTO babysitters (name, email, password, photo, hourly_rate, experience, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $password, $photo, $hourlyRate, $experience, $location);
        $stmt->execute();

        // Redirecionar para o dashboard da babá
        $_SESSION['user_id'] = $stmt->insert_id; // Armazenar o ID do usuário para a sessão
        $_SESSION['user_type'] = 'baba'; // Armazenar o tipo de usuário na sessão
        header('Location: dashboard_baba.php');
        exit;
    
    // Para o responsável
    } elseif ($userType === 'responsavel') {
        $name = $_POST['responsavelName'];
        $password = password_hash($_POST['responsavelPassword'], PASSWORD_DEFAULT);
        $location = $_POST['responsavelLocation']; // Supondo que aqui já esteja no formato "Lat: x, Lon: y"

        // Salvar dados do responsável no banco
        $stmt = $conn->prepare("INSERT INTO guardians (name, email, password, location) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $location);
        $stmt->execute();

        // Redirecionar para o dashboard do responsável
        $_SESSION['user_id'] = $stmt->insert_id; // Armazenar o ID do usuário para a sessão
        $_SESSION['user_type'] = 'responsavel'; // Armazenar o tipo de usuário na sessão
        header('Location: dashboard_responsavel.php');
        exit;
    }

    // Fechar a declaração e a conexão
    $stmt->close();
    $conn->close();
}
?>
