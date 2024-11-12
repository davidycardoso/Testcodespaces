<?php
session_start();
require 'conexao.php'; // Inclua sua conexão com o banco de dados

// Verifique se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Verifica se o tipo de usuário é babá ou responsável
$isBabysitter = $_SESSION['user_type'] === 'babysitter';
$isGuardian = $_SESSION['user_type'] === 'guardian';

// Se não for nem babá nem responsável, redirecione
if (!$isBabysitter && !$isGuardian) {
    header('Location: index.php');
    exit;
}

// Lidar com a submissão do formulário de avaliação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $comment = htmlspecialchars($_POST['comment']);
    
    if ($isBabysitter) {
        $guardianId = intval($_POST['guardian_id']); // ID do responsável
        $stmt = $conn->prepare("INSERT INTO reviews (babysitter_id, guardian_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $_SESSION['user_id'], $guardianId, $rating, $comment);
    } elseif ($isGuardian) {
        $babysitterId = intval($_POST['babysitter_id']); // ID da babá
        $stmt = $conn->prepare("INSERT INTO reviews (guardian_id, babysitter_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $_SESSION['user_id'], $babysitterId, $rating, $comment);
    }

    if ($stmt->execute()) {
        echo "<p>Avaliação enviada com sucesso!</p>";
    } else {
        echo "<p>Erro ao enviar a avaliação. Tente novamente.</p>";
    }
}

// Carregar informações do responsável ou babá a ser avaliado
$targetId = $_GET['id']; // ID da babá ou responsável
$targetType = $isBabysitter ? 'guardian' : 'babysitter';

$query = $conn->prepare("SELECT * FROM $targetType WHERE id = ?");
$query->bind_param("i", $targetId);
$query->execute();
$result = $query->get_result();
$target = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Avaliação</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h1>Avaliar <?php echo $isBabysitter ? 'Responsável' : 'Babá'; ?></h1>
    <form method="POST" action="">
        <input type="hidden" name="<?php echo $isBabysitter ? 'guardian_id' : 'babysitter_id'; ?>" value="<?php echo $target['id']; ?>">
        
        <label for="rating">Nota (1 a 5):</label>
        <select name="rating" required>
            <option value="">Escolha uma nota</option>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        
        <label for="comment">Comentário:</label>
        <textarea name="comment" rows="4" required></textarea>
        
        <button type="submit" class="button">Enviar Avaliação</button>
    </form>
</body>
</html>
