<?php
// mensagens.php

require_once 'conexao.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Defina se o usuário é responsável ou babá para a consulta
$user_type = $_SESSION['user_type'];
$other_user_type = ($user_type === 'responsavel') ? 'babá' : 'responsável';

// Obter os usuários com quem o usuário atual pode trocar mensagens
$query_users = ($user_type === 'responsavel')
    ? "SELECT * FROM babysitters"
    : "SELECT * FROM guardians";

$users_result = $conn->query($query_users);

// Obter mensagens relacionadas ao usuário
$query_messages = $conn->prepare("SELECT * FROM messages WHERE sender_id = ? OR receiver_id = ?");
$query_messages->bind_param("ii", $user_id, $user_id);
$query_messages->execute();
$messages = $query_messages->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagens</title>
    <link rel="stylesheet" href="css/mensagens.css"> <!-- Link para o CSS -->
</head>
<body>
    <header>
        <h1>Mensagens</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <h2>Conversas</h2>
        <ul>
            <?php while ($msg = $messages->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($msg['sender_id'] === $user_id ? "Você" : "Usuário"); ?>:</strong>
                    <?php echo htmlspecialchars($msg['content']); ?> <small><?php echo htmlspecialchars($msg['created_at']); ?></small>
                </li>
            <?php endwhile; ?>
        </ul>

        <h2>Enviar Mensagem</h2>
        <form method="post" action="enviar_mensagem.php">
            <select name="receiver_id" required>
                <option value="">Escolha um destinatário</option>
                <?php while ($user = $users_result->fetch_assoc()): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
                <?php endwhile; ?>
            </select>
            <input type="text" name="message" placeholder="Digite sua mensagem" required>
            <button type="submit">Enviar</button>
        </form>
    </main>
</body>
</html>
