<?php
require 'vendor/autoload.php';
include('config.php'); // Inclure config.php pour la connexion à la base de données

$result = $conn->query("SELECT username, score FROM scores ORDER BY score DESC LIMIT 1");
$row = $result->fetch_assoc();

echo json_encode(['username' => $row['username'], 'score' => $row['score']]);

$conn->close();
?>
