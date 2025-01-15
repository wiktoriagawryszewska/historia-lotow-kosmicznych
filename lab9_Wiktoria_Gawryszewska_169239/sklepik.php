<?php
if (!defined('IN_INDEX')) {
    header('Location: index.php?idp=shop');
    exit();
}

// Włączanie wyświetlania błędów dla debugowania
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Inicjalizacja sesji (upewnij się, że jest już w index.php lub cfg.php)

/**
 * Funkcja wyświetlająca produkty w sklepie z obrazkami
 */
function PokazProdukty() {
    global $conn;
    $query = "SELECT tytul, cena_netto, zdjecie FROM produkty2";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Błąd zapytania: " . mysqli_error($conn));
    }

    echo '<h2>Produkty w sklepie</h2>';
    echo '<div style="display: flex; flex-wrap: wrap; gap: 20px;">';

    while ($produkt = mysqli_fetch_assoc($result)) {
        echo '<div style="border: 1px solid #ddd; padding: 10px; width: 200px; text-align: center;">';
        echo '<img src="' . htmlspecialchars($produkt['zdjecie']) . '" alt="' . htmlspecialchars($produkt['tytul']) . '" style="width: 100%; height: auto;">';
        echo '<h3>' . htmlspecialchars($produkt['tytul']) . '</h3>';
        echo '<p>Cena: ' . number_format($produkt['cena_netto'], 2) . ' zł</p>';
        echo '<form action="index.php?idp=shop" method="post">';
        echo '<input type="hidden" name="produkt" value="' . htmlspecialchars($produkt['tytul']) . '">';
        echo '<input type="hidden" name="cena_netto" value="' . htmlspecialchars($produkt['cena_netto']) . '">';
        echo '<button type="submit" name="addToCart" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">Dodaj do koszyka</button>';
        echo '</form>';
        echo '</div>';
    }

    echo '</div>';
    echo '<a href="index.php?idp=cart" style="display: block; margin: 20px 0; padding: 10px; background-color: #007bff; color: white; text-align: center; text-decoration: none; border-radius: 5px;">Przejdź do koszyka</a>';


}

/**
 * Funkcja obsługująca dodawanie produktu do koszyka
 */
function DodajDoKoszyka() {
    
    // Pobieranie danych produktu z formularza
    $produkt = htmlspecialchars($_POST['produkt'] ?? '');
    $cena = floatval($_POST['cena_netto'] ?? 0);

    if (empty($produkt) || $cena <= 0) {
        echo "<p style='color: red;'>Nie wybrano poprawnego produktu. Spróbuj ponownie.</p>";
        return;
    }

    // Inicjalizacja koszyka, jeśli nie istnieje
    if (!isset($_SESSION['koszyk'])) {
        $_SESSION['koszyk'] = [];
    }

    // Dodawanie produktu do koszyka
    $znaleziono = false;
    foreach ($_SESSION['koszyk'] as &$item) {
        if ($item['nazwa'] === $produkt) {
            $item['ilosc']++;
            $znaleziono = true;
            break;
        }
    }

    if (!$znaleziono) {
        $_SESSION['koszyk'][] = ['nazwa' => $produkt, 'ilosc' => 1, 'cena' => $cena];
    }

    echo "<p style='color: green;'>Dodano produkt do koszyka: $produkt</p>";
}



// Wyświetlenie produktów
PokazProdukty();
?>
