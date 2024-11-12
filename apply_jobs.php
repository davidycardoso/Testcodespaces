<?php
session_start();
require 'conexao.php'; // Arquivo com a conexão ao banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = $_POST['job_id'];
    $babysitter_id = $_SESSION['user_id']; // ID da babá logada

    // Inserção da candidatura
    $stmt = $pdo->prepare("INSERT INTO applications (job_id, babysitter_id) VALUES (?, ?)");
    $stmt->execute([$job_id, $babysitter_id]);

    echo "Candidatura enviada com sucesso!";
    header("Location: find_jobs.php"); // Redireciona de volta para a página de trabalhos
    exit();
}
?>
