<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'moja_strona';

$conn = mysqli_connect($host, $user, $password, $database);

$login = "admin"; // Twój login
$pass = "12345";  // Twoje hasło


if (!$conn) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}
?>
