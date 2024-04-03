<?php
$host = getenv('DB_HOST');
$dbname = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM `114-fdpg-test`");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo implode(" | ", $row) . "\n";
    }
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
