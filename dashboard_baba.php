<?php
// Conexão com o banco de dados
$pdo = new mysqli('localhost', 'root', '', 'babybuddy');

if ($pdo->connect_error) {
    die("Erro de conexão: " . $pdo->connect_error);
}

// Exemplo de consulta SQL (substitua '2' pela variável correta, se necessário)
$query = "SELECT * FROM babysitters WHERE id = ?"; // Ajuste esta consulta conforme necessário
$stmt = $pdo->prepare($query);

if ($stmt === false) {
    die("Erro na preparação da consulta: " . $pdo->error);
}

$babysitterId = 1; // Exemplo de ID, ajuste conforme necessário
$stmt->bind_param('i', $babysitterId); // 'i' indica que o parâmetro é um inteiro

if (!$stmt->execute()) {
    die("Erro na execução da consulta: " . $stmt->error);
}

$result = $stmt->get_result();
$babysitter = $result->fetch_assoc(); // Dados da babá

// Consulta para empregos (ajuste conforme necessário)
$jobsQuery = "SELECT * FROM jobs WHERE babysitter_id = ?"; // Assumindo que 'babysitter_id' existe na tabela 'jobs'
$jobsStmt = $pdo->prepare($jobsQuery);
$jobsStmt->bind_param('i', $babysitterId);
$jobsStmt->execute();
$jobsResult = $jobsStmt->get_result();

// Consultar mensagens
$messagesQuery = "SELECT * FROM messages WHERE recipient_id = ?"; // Supondo que o campo recipient_id seja o ID da babá
$messagesStmt = $pdo->prepare($messagesQuery);
$messagesStmt->bind_param('i', $babysitterId);
$messagesStmt->execute();
$messagesResult = $messagesStmt->get_result();

// Consultar avaliações
$reviewsQuery = "SELECT * FROM reviews WHERE babysitter_id = ?"; // Assumindo que 'babysitter_id' exista na tabela 'reviews'
$reviewsStmt = $pdo->prepare($reviewsQuery);
$reviewsStmt->bind_param('i', $babysitterId);
$reviewsStmt->execute();
$reviewsResult = $reviewsStmt->get_result();

// Consultar finanças
$financesQuery = "SELECT SUM(amount) AS total FROM finances WHERE babysitter_id = ?"; // Exemplo de consulta para finanças
$financesStmt = $pdo->prepare($financesQuery);
$financesStmt->bind_param('i', $babysitterId);
$financesStmt->execute();
$financeResult = $financesStmt->get_result()->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard da Babá</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href='https://fullcalendar.io/releases/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
    <script src='https://fullcalendar.io/releases/fullcalendar/3.10.2/fullcalendar.min.js'></script>
</head>
<body>
    <header>
        <h1>Bem-vinda, <?php echo htmlspecialchars($babysitter['name']); ?></h1>
        <a href="logout.php" class="logout">Logout</a>
    </header>
    <nav>
        <ul>
            <li><a href="#profile">Perfil</a></li>
            <li><a href="#jobs">Meus Empregos</a></li>
            <li><a href="#schedule">Agenda</a></li>
            <li><a href="#messages">Mensagens</a></li>
            <li><a href="#reviews">Avaliações</a></li>
            <li><a href="#finances">Finanças</a></li>
        </ul>
    </nav>
    <main>
    <section id="profile">
    <h2>Meu Perfil</h2>
    <div class="profile-container">
        <img src="<?php echo htmlspecialchars($babysitter['photo']); ?>" alt="Foto da Babá" class="profile-photo">
        <div class="profile-details">
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($babysitter['name']); ?></p>
            <p><strong>Taxa horária:</strong> R$ <?php echo htmlspecialchars($babysitter['hourly_rate']); ?></p>
            <p><strong>Experiência:</strong> <?php echo htmlspecialchars($babysitter['experience']); ?></p>
            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($babysitter['description'] ?? 'Nenhuma descrição disponível.'); ?></p>
            <p><strong>Contato:</strong></p>
            <p>Telefone: <?php echo htmlspecialchars($babysitter['phone'] ?? 'Não disponível'); ?></p>
            <p>E-mail: <?php echo htmlspecialchars($babysitter['email'] ?? 'Não disponível'); ?></p>
        </div>
    </div>
    <div class="profile-actions">
        <a href="edit_profile.php" class="button">Editar Perfil</a>
        <a href="view_reviews.php?id=<?php echo $babysitter['id']; ?>" class="button">Ver Avaliações</a>
        <a href="services.php" class="button">Ver Serviços Oferecidos</a>
        <a href="upload_photo.php" class="button">Adicionar Nova Foto de Perfil</a>
    </div>
</section>

        
        <section id="jobs">
            <h2>Meus Empregos</h2>
            <ul>
                <?php while ($job = $jobsResult->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($job['title']); ?> - <?php echo htmlspecialchars($job['date']); ?></li>
                <?php endwhile; ?>
            </ul>
            <a href="available_jobs.php" class="button">Ver Empregos Disponíveis</a>
        </section>
        
        <section id="schedule">
    <h2>Minha Agenda</h2>
    <div id='calendar'></div>
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                events: 'load_events.php',
                editable: true,
                header: { left: 'prev,next today', center: 'title', right: 'month,agendaWeek,agendaDay' },
                eventLimit: true
            });
        });
    </script>
    <form method="POST" action="add_event.php">
        <label for="title">Título:</label>
        <input type="text" id="title" name="title" required>
        <label for="start">Início:</label>
        <input type="datetime-local" id="start" name="start" required>
        <label for="end">Fim:</label>
        <input type="datetime-local" id="end" name="end" required>
        <button type="submit" class="button">Adicionar Evento</button>
    </form>
    <a href="view_events.php" class="button">Ver Eventos Criados</a> <!-- Botão para ver eventos -->
</section>

        <section id="messages">
            <h2>Mensagens</h2>
            <ul>
                <?php while ($message = $messagesResult->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($message['content']); ?> - <small><?php echo htmlspecialchars($message['created_at']); ?></small></li>
                <?php endwhile; ?>
            </ul>
            <form method="POST" action="send_message.php">
                <textarea name="content" required></textarea>
                <input type="hidden" name="receiver_id" value="<?php echo $babysitter['id']; ?>">
                <button type="submit" class="button">Enviar Mensagem</button>
            </form>
        </section>
        
        <section id="reviews">
            <h2>Avaliações</h2>
            <ul>
                <?php while ($review = $reviewsResult->fetch_assoc()): ?>
                    <li>Nota: <?php echo htmlspecialchars($review['rating']); ?> - <?php echo htmlspecialchars($review['comment']); ?> - <small><?php echo htmlspecialchars($review['created_at']); ?></small></li>
                <?php endwhile; ?>
            </ul>
        </section>
        
       
