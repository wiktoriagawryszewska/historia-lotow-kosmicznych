<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Włączanie wyświetlania błędów dla debugowania
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obsługa przesłania formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Walidacja danych
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "<p>Wszystkie pola formularza są wymagane.</p>";
    } else {
        // Wysyłanie wiadomości
        $to = "twoj-email@example.com"; // Zmień na swój adres email
        $fullMessage = "Od: $name\nEmail: $email\nTemat: $subject\n\nWiadomość:\n$message";
        $headers = "From: $email";

        if (mail($to, $subject, $fullMessage, $headers)) {
            echo "<p>Wiadomość została wysłana pomyślnie!</p>";
        } else {
            echo "<p>Błąd wysyłania wiadomości. Spróbuj ponownie później.</p>";
        }
    }
}
?>

<h2>Skontaktuj się z nami</h2>
<form action="index.php?idp=contact" method="post">
    <label for="name">Imię:</label><br>
    <input type="text" id="name" name="name" required><br>
    
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br>
    
    <label for="subject">Temat:</label><br>
    <input type="text" id="subject" name="subject" required><br>
    
    <label for="message">Wiadomość:</label><br>
    <textarea id="message" name="message" required></textarea><br>
    
    <input type="submit" value="Wyślij">
</form>
