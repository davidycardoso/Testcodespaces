<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";  // Sem senha
$dbname = "babybuddy";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Definir o charset para a conexão
if (!$conn->set_charset("utf8")) {
    echo "Erro ao definir o charset: " . $conn->error;
}

// Função para fechar a conexão
function fecharConexao($conn) {
    $conn->close();
}
?>