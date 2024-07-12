<?php
session_start();
require 'vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env');
    $dotenv->load();
} elseif (file_exists(__DIR__ . '/.env.production')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.production');
    $dotenv->load();
}

$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$welcome_message = "Bienvenue, " . htmlspecialchars($_SESSION['username']) . "!";

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Jeu de Correspondance Aliment-Bienfait</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?php echo $welcome_message; ?></h1>
    <h2>Associez chaque aliment à son bienfait</h2>
    <div class="container">
        <div id="foods" class="items">
            <!-- Les aliments seront générés ici par JavaScript -->
        </div>
        <div id="benefits" class="items">
            <!-- Les bienfaits seront générés ici par JavaScript -->
        </div>
    </div>
    <?php if ($_SESSION['role'] == 'admin'): ?>
    <div class="admin-options">
        
        <a href="add_food.html" class="admin-link">Ajouter un aliment</a>
        <a href="#" id="show-delete-options" class="admin-link">Supprimer un aliment</a>
    </div>
    <?php endif; ?>
    <div id="score-board">
        <!-- Le tableau des scores sera généré ici par JavaScript -->
    </div>
    <div id="best-score-board">
        <!-- Le meilleur score sera généré ici par JavaScript -->
    </div>
    <div class="game-controls">
        <button id="restart-game">Recommencer</button>
    </div>
    <a href="logout.php" class="logout-link">Déconnexion</a>
    <script>
        window.gameConfig = {
            currentUser: "<?php echo $_SESSION['username']; ?>",
            currentRole: "<?php echo $_SESSION['role']; ?>"
        };
    </script>
    <script src="script.js"></script>
</body>
</html>
