<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$debug_messages = [];

// Ajout des messages de débogage dans un tableau
$debug_messages[] = 'Starting script';

require 'vendor/autoload.php';

$debug_messages[] = 'Autoload loaded';

// Chargement des variables d'environnement
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env');
    $dotenv->load();
    $debug_messages[] = '.env loaded';
} elseif (file_exists(__DIR__ . '/.env.production')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.production');
    $dotenv->load();
    $debug_messages[] = '.env.production loaded';
} else {
    echo json_encode(['success' => false, 'message' => 'No .env file found', 'debug' => $debug_messages]);
    exit();
}

$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

$debug_messages[] = 'DB credentials loaded';

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error, 'debug' => $debug_messages]);
    exit();
}

$debug_messages[] = 'Connected to DB';

// Exécution de la requête SQL pour récupérer les aliments
$sql = "SELECT id, name, benefit FROM foods";
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode(['success' => false, 'message' => 'Error executing query: ' . $conn->error, 'debug' => $debug_messages]);
    $conn->close();
    exit();
}

$debug_messages[] = 'Query executed';

$foods = [];
while ($row = $result->fetch_assoc()) {
    $foods[] = $row;
}

if (empty($foods)) {
    echo json_encode(['success' => false, 'message' => 'No foods found', 'debug' => $debug_messages]);
    $conn->close();
    exit();
}

$response = [
    'success' => true,
    'foods' => $foods,
    'role' => isset($_SESSION['role']) ? $_SESSION['role'] : 'user',
    'debug' => $debug_messages
];

echo json_encode($response);
$conn->close();
?>
