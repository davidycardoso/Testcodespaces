<?php
session_start(); // Inicia a sessão

// Verifica se há uma sessão ativa
if (isset($_SESSION['user_id'])) {
    // Destrói a sessão
    session_unset(); // Remove todas as variáveis de sessão
    session_destroy(); // Destroi a sessão

    // Redireciona para a página de login
    header("Location: login.php");
    exit();
} else {
    // Se não houver sessão ativa, redireciona para a página de login
    header("Location: login.php");
    exit();
}
?>
