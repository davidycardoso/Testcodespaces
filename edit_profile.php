<?php
// Conexão com o banco de dados
require_once 'conexao.php';
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtém o tipo de usuário
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type']; // 'baba' ou 'responsavel'

// Obter informações do usuário
if ($user_type === 'baba') {
    $query = $conn->prepare("SELECT * FROM babysitters WHERE id = ?");
} else {
    $query = $conn->prepare("SELECT * FROM guardians WHERE id = ?");
}

$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Atualiza o perfil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($user_type === 'baba') {
        $name = $_POST['name'];
        $hourly_rate = $_POST['hourly_rate'];
        $qualifications = $_POST['qualifications'];
        $experience = $_POST['experience'];
        
        $update_query = $conn->prepare("UPDATE babysitters SET name = ?, hourly_rate = ?, qualifications = ?, experience = ? WHERE id = ?");
        $update_query->bind_param("sdssi", $name, $hourly_rate, $qualifications, $experience, $user_id);
    } else {
        $name = $_POST['name'];
        
        $update_query = $conn->prepare("UPDATE guardians SET name = ? WHERE id = ?");
        $update_query->bind_param("si", $name, $user_id);
    }

    if ($update_query->execute()) {
        if ($user_type === 'baba') {
            header("Location: dashboard_baba.php"); // Redireciona para o dashboard da babá
        } else {
            header("Location: dashboard_responsavel.php"); // Redireciona para o dashboard do responsável
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/edit_profile.css"> <!-- Link para o CSS -->
</head>
<body>
    <header>
        <h1>Editar Perfil</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <form method="POST" action="edit_profile.php">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <?php if ($user_type === 'baba'): ?>
                <label for="hourly_rate">Taxa Horária:</label>
                <input type="number" id="hourly_rate" name="hourly_rate" step="0.01" value="<?php echo htmlspecialchars($user['hourly_rate']); ?>" required>

                <label for="qualifications">Qualificações:</label>
                <textarea id="qualifications" name="qualifications" required><?php echo htmlspecialchars($user['qualifications']); ?></textarea>

                <label for="experience">Experiência:</label>
                <textarea id="experience" name="experience" required><?php echo htmlspecialchars($user['experience']); ?></textarea>
            <?php endif; ?>

            <button type="submit">Atualizar</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 BabyBuddy</p>
    </footer>
</body>
</html>
