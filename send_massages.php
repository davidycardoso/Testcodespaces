<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $babysitter_id = $_SESSION['user_id']; // ID da babá

    $stmt = $conn->prepare("INSERT INTO messages (babysitter_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $babysitter_id, $message);
    $stmt->execute();

    echo "Mensagem enviada com sucesso!";
}
?>
