<?php
session_start();
require_once 'conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM events WHERE user_id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while($row = $result->fetch_assoc()) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end']
    ];
}

echo json_encode($events);
?>
