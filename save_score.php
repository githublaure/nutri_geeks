<?php
require 'vendor/autoload.php';
include('config.php'); // Inclure config.php pour la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $score = $data['score'];

    $stmt = $conn->prepare("INSERT INTO scores (username, score) VALUES (?, ?)");
    $stmt->bind_param("si", $username, $score);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving score']);
    }

    $stmt->close();
}

$conn->close();
?>
