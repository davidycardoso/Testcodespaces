<?php
// enviar_mensagem.php

require_once 'conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $content = $_POST['message'];

    $query_insert = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
    $query_insert->bind_param("iis", $sender_id, $receiver_id, $content);

    if ($query_insert->execute()) {
        header("Location: mensagens.php"); // Redireciona de volta para a página de mensagens
        exit();
    } else {
        echo "Erro ao enviar a mensagem.";
    }
} else {
    header("Location: mensagens.php");
    exit();
}
?>
