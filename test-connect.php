<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_DATABASE');
echo "1:". $dbname;
$dbname = $_ENV['DB_DATABASE'];
echo "2:". $dbname;
$dbname = $_SERVER['DB_DATABASE'];
echo "3:". $dbname;

exit;
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM `114-fdpg-test`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo implode(" | ", $row) . "\n";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
