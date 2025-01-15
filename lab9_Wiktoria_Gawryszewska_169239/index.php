<?php
// Projekt: Historia Lotów Kosmicznych
// Wersja: v1.8
// Autor: Wiktoria Gawryszewska, nr indeksu: 169239, grupa 1

define('IN_INDEX', true); // Flaga informująca, że plik jest ładowany przez index.php
include('cfg.php'); // Dołączanie konfiguracji
include('showpage.php');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); // Wyłączenie wyświetlania niekrytycznych błędów

/**
 * Funkcja decydująca, który plik zostanie załadowany na podstawie parametru `idp`.
 */
$idp = htmlspecialchars($_GET['idp'] ?? ''); // Zabezpieczenie zmiennej `idp`
// Decyzja, który plik załadować
// switch ($idp) {
//     case '':
//         $strona = 'html/glowna.html';
//         break;
//     case 'about':
//         $strona = 'html/about.html';
//         break;
//     case 'gallery':
//         $strona = 'html/gallery.html';
//         break;
//     case 'services':
//         $strona = 'html/services.html';
//         break;
//     case 'future':
//         $strona = 'html/future.html';
//         break;
//     case 'contact':
//         $strona = 'contact.php'; // Ładowanie pliku kontaktowego
//         break;
//     case 'admin1':
//         $strona = 'admin1.php'; // Ładowanie panelu admina
//         break;
//     case 'films':
//         $strona = 'html/films.html';
//         break;
//     case 'shop':
//         $strona = 'sklepik.php'; // Ładowanie pliku sklepu
//         break;
//     case 'cart':
//         $strona = 'koszyk.php'; // Ładowanie pliku koszyka
//         break;
//     default:
//         $strona = 'html/blad.html'; // Plik z informacją o błędzie
//         break;
// }

// // Sprawdzenie, czy plik istnieje
// if (!file_exists($strona)) {
//     $strona = 'html/blad.html';
// }
// ?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Lotów Kosmicznych</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Odwołanie do pliku CSS -->
    <script src="js/kolorujtlo.js" defer></script>
    <script src="js/timedate.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body onload="startclock()">
<div id="zegarek"></div>
<div id="data"></div>

<header>
    <h1>Historia Lotów Kosmicznych</h1>
</header>

<!-- Nawigacja strony -->
<nav>
    <ul>
        <li class="<?php
        echo $idp === '4' ? 'active' : ''; ?>"><a href="index.php?idp=4">Home</a></li>
        <li class="<?php
        echo $idp === '1' ? 'active' : ''; ?>"><a href="index.php?idp=1">Historia</a></li>
        <li class="<?php
        echo $idp === '2' ? 'active' : ''; ?>"><a href="index.php?idp=2">Galeria Kosmiczna</a></li>
        <li class="<?php
        echo $idp === '7' ? 'active' : ''; ?>"><a href="index.php?idp=7">Programy Kosmiczne</a></li>
        <li class="<?php
        echo $idp === '6' ? 'active' : ''; ?>"><a href="index.php?idp=6">Przyszłość</a></li>
        <li class="<?php
        echo $idp === 'contact' ? 'active' : ''; ?>"><a href="index.php?idp=contact">Kontakt</a></li>
        <li class="<?php
        echo $idp === '5' ? 'active' : ''; ?>"><a href="index.php?idp=5">Filmy</a></li>
        <li class="<?php
        echo $idp === '8' ? 'active' : ''; ?>"><a href="index.php?idp=8">Logowanie</a></li>
        <li class="<?php
        echo $idp === 'shop' ? 'active' : ''; ?>"><a href="index.php?idp=shop">Sklepik</a></li>
        <li class="<?php
        echo $idp === 'cart' ? 'active' : ''; ?>"><a href="index.php?idp=cart">Koszyk</a></li>
    </ul>
</nav>

<!-- Główna treść strony -->
<main>
    <?php
    if ($idp === 'contact'){
        include 'contact.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['sendMail'])) {
                WyslijMailKontakt();
            } elseif (isset($_POST['remindPassword'])) {
                PrzypomnijHaslo();
            }
        } else {
            PokazKontakt();
        }
    } elseif ($idp === 'shop'){
        include 'sklepik.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['addToCart'])) {
                DodajDoKoszyka();
            }
        }
    } elseif ($idp === 'cart') {
        include 'koszyk.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['removeFromCart'])) {
                UsunZKoszyka($_POST['removeFromCart']);
            } elseif (isset($_POST['updateCart'])) {
                AktualizujKoszyk($_POST['ilosc']);
            } elseif (isset($_POST['finalizeCart'])) {
                FinalizujKoszyk();
            }
        }
    } else {
        PokazPodstrone($idp);
    }

    ?>
</main>

<!-- Stopka strony -->
<footer>
    <p>&copy; 2024 Historia Lotów Kosmicznych. Wszystkie prawa zastrzeżone.</p>
</footer>

<!-- Skrypty animacji -->
<script>
    $("#animacjal").on("click", function () {
        $(this).animate({
            width: "500px",
            opacity: 0.4,
            fontSize: "3em",
            borderWidth: "10px"
        }, 1500);
    });

    $("#animacja2").on({
        "mouseover": function () {
            $(this).animate({
                width: "300px"
            }, 800);
        },
        "mouseout": function () {
            $(this).animate({
                width: "200px"
            }, 800);
        }
    });

    $("#animacja3").on("click", function () {
        if (!$(this).is(":animated")) {
            $(this).animate({
                width: "+=50",
                height: "+=50",
                opacity: 0.1
            }, 3000);
        }
    });
</script>

<?php
// Informacje o autorze projektu
$nr_indeksu = '169239';
$nrGrupy = '1';
echo 'Autor: Wiktoria Gawryszewska, indeks: ' . $nr_indeksu . ', grupa: ' . $nrGrupy . ' <br /><br />';
?>
</body>
</html>
