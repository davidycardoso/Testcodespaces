<?php
session_start(); // Inicia a sessão

// Verifique se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'conexao.php'; // Conexão com o banco de dados

    // Verifique se os dados foram passados corretamente
    if (isset($_POST['title'], $_POST['start'], $_POST['end'])) {
        $title = $_POST['title'];
        $start = $_POST['start'];
        $end = $_POST['end'];

        // Verifique se o 'user_id' está presente na sessão
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            // Prepare a consulta SQL para inserir o evento
            $query = $conn->prepare("INSERT INTO events (title, start, end, user_id) VALUES (?, ?, ?, ?)");

            // Verifique se a preparação da consulta foi bem-sucedida
            if (!$query) {
                die("Erro na preparação da consulta: " . $conn->error);
            }

            // Associe os parâmetros à consulta (strings para title, start, end; e inteiro para user_id)
            $query->bind_param("sssi", $title, $start, $end, $user_id);

            // Execute a consulta
            if ($query->execute()) {
                // Redirecionar após a inserção
                header("Location: dashboard_baba.php#schedule");
                exit();
            } else {
                echo "Erro ao inserir evento: " . $query->error;
            }

            // Fechar a consulta
            $query->close();
        } else {
            echo "Erro: Usuário não está logado.";
        }
    } else {
        echo "Erro: Campos do evento não preenchidos corretamente.";
    }

    // Fechar a conexão com o banco
    $conn->close();
}
?>
