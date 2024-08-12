<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestao_mrg";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
