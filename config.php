 <?php
// ================== DB CONFIG ==================
$host = "localhost";
$db   = "agrimarket";
$user = "root";
$pass = "";

try {
    // Create PDO instance
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch associative arrays by default
            PDO::ATTR_EMULATE_PREPARES   => false                   // use native prepared statements
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
