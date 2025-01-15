<?php

if (!defined('IN_INDEX')) {
    die('Bezpośredni dostęp do tego pliku jest zabroniony.');
}

// Włączanie wyświetlania błędów dla debugowania
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Uruchomienie sesji dla obsługi koszyka

/**
 * Funkcja wyświetlająca zawartość koszyka
 */
function PokazKoszyk() {
    echo '<h1>Twój koszyk 🛒</h1>';
    
    // Sprawdzenie, czy koszyk jest pusty
    if (!isset($_SESSION['koszyk']) || count($_SESSION['koszyk']) === 0) {
        echo '<p>Koszyk jest pusty.</p>';
        echo '<a href="index.php?idp=shop" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Wróć do sklepu</a>';
        return;
    }

    $produkty = [
        "Kubek z planetami" => 35.00,
        "Kubek z rakietą" => 35.00,
        "Koszulka biała" => 150.00,
        "Breloczek z rakietą" => 30.00,
        "Skarpetki z astronautą" => 50.00,
        "Koszulka czarna" => 150.00
    ];

    $suma = 0;

    // Formularz koszyka
    echo '<form action="index.php?idp=cart" method="post">';
    echo '<ul style="list-style: none; padding: 0;">';

    foreach ($_SESSION['koszyk'] as $index => $produkt) {
        if (!is_array($produkt) || !isset($produkt['nazwa'], $produkt['ilosc'])) {
            continue; // Pomijanie nieprawidłowych danych w koszyku
        }

        $cena = $produkty[$produkt['nazwa']] ?? 0; // Pobranie ceny produktu
        $wartosc = $cena * $produkt['ilosc']; // Wartość produktu
        $suma += $wartosc;

        echo '<li style="margin: 10px 0; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">';
        echo '<span>' . htmlspecialchars($produkt['nazwa']) . ' - ' . number_format($cena, 2) . ' zł/szt.</span>';
        echo ' <input type="number" name="ilosc[' . $index . ']" value="' . $produkt['ilosc'] . '" min="1" style="width: 50px; margin-left: 10px;">';
        echo ' <button type="submit" name="removeFromCart" value="' . $index . '" style="padding: 5px 10px; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">Usuń</button>';
        echo '</li>';
    }
    echo '</ul>';

    // Wyświetlenie sumy
    echo '<h3>Suma: ' . number_format($suma, 2) . ' zł</h3>';

    // Przyciski
    echo '<button type="submit" name="updateCart" style="padding: 10px 20px; background-color: #ffc107; color: white; border: none; border-radius: 5px; cursor: pointer;">Zaktualizuj koszyk</button>';
    echo ' <button type="submit" name="finalizeCart" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">Przejdź do finalizacji</button>';
    echo '</form>';

    echo '<a href="index.php?idp=shop" style="display: block; margin-top: 20px; padding: 10px; background-color: #007bff; color: white; text-align: center; text-decoration: none; border-radius: 5px;">Wróć do sklepu</a>';
}

/**
 * Funkcja usuwająca produkt z koszyka
 */
function UsunZKoszyka($index) {
    if (isset($_SESSION['koszyk'][$index])) {
        unset($_SESSION['koszyk'][$index]);
        $_SESSION['koszyk'] = array_values($_SESSION['koszyk']); // Przebudowa indeksów
        echo '<p>Produkt został usunięty z koszyka.</p>';
    }
}

/**
 * Funkcja aktualizująca ilość produktów w koszyku
 */
function AktualizujKoszyk($ilosci) {
    foreach ($ilosci as $index => $ilosc) {
        if (isset($_SESSION['koszyk'][$index]) && $ilosc > 0) {
            $_SESSION['koszyk'][$index]['ilosc'] = intval($ilosc);
        }
    }
    echo '<p>Koszyk został zaktualizowany.</p>';
}

/**
 * Funkcja finalizująca koszyk
 */
function FinalizujKoszyk() {
    echo '<h1>Dziękujemy za zakupy!</h1>';
    echo '<p>Twoje zamówienie zostało zrealizowane.</p>';
    echo '<p>😊</p>'; // Wyświetlanie uśmiechu jako potwierdzenie
    unset($_SESSION['koszyk']); // Opróżnienie koszyka
}

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['removeFromCart'])) {
        UsunZKoszyka($_POST['removeFromCart']);
    } elseif (isset($_POST['updateCart'])) {
        AktualizujKoszyk($_POST['ilosc']);
    } elseif (isset($_POST['finalizeCart'])) {
        FinalizujKoszyk();
        return;
    }
}

// Wyświetlenie koszyka
PokazKoszyk();
