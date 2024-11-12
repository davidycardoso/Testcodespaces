<?php
include 'conexao.php'; // Inclua seu arquivo de conexão

// Consultar empregos disponíveis
$query = "SELECT * FROM jobs"; // Ajuste o nome da tabela conforme necessário
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Empregos Disponíveis</title>
    <link rel="stylesheet" href="style.css"> <!-- Seu CSS -->
</head>
<body>
    <h1>Empregos Disponíveis</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Localização</th>
            <th>Ação</th>
        </tr>
        <?php while ($job = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $job['id']; ?></td>
            <td><?php echo $job['description']; ?></td>
            <td><?php echo $job['location']; ?></td>
            <td><a href="apply_job.php?id=<?php echo $job['id']; ?>">Candidatar-se</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
