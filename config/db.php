<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "operating_system";

$conn = mysqli_connect($host, $user, $pass, $dbname);
?>

<?php
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
