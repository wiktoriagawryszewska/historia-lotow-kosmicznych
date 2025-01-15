<?php
// Projekt: Historia Lotów Kosmicznych
// Wersja: v1.8
// Autor: Wiktoria Gawryszewska, nr indeksu: 169239, grupa 1

include 'cfg.php'; // Dołączanie konfiguracji
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); // Wyłączenie wyświetlania niekrytycznych błędów
//phpinfo();exit;
/**
 * Funkcja decydująca, który plik zostanie załadowany na podstawie parametru `idp`.
 */
if (isset($_GET['idp'])) {
    $idp = htmlspecialchars($_GET['idp']); // Zabezpieczenie zmiennej `idp`
} else {
    $idp = ''; // Domyślna wartość
}

// Decyzja, który plik załadować
switch ($idp) {
    case '':
        $strona = 'html/glowna.html';
        break;
    case 'about':
        $strona = 'html/about.html';
        break;
    case 'gallery':
        $strona = 'html/gallery.html';
        break;
    case 'services':
        $strona = 'html/services.html';
        break;
    case 'future':
        $strona = 'html/future.html';
        break;
    case 'contact':
        $strona = 'contact.php'; // Ładowanie pliku PHP
        break;
    case 'films':
        $strona = 'html/films.html';
        break;
    default:
        $strona = 'html/blad.html'; // Plik z informacją o błędzie
        break;
}

// Sprawdzenie, czy plik istnieje
if (!file_exists($strona)) {
    $strona = 'html/blad.html';
}

echo "Połączenie z bazą danych jest aktywne.";
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Lotów Kosmicznych</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/kolorujtlo.js" defer></script>
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
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="index.php?idp=about">Historia</a></li>
            <li><a href="index.php?idp=gallery">Galeria Kosmiczna</a></li>
            <li><a href="index.php?idp=services">Programy Kosmiczne</a></li>
            <li><a href="index.php?idp=future">Przyszłość</a></li>
            <li><a href="index.php?idp=contact">Kontakt</a></li>
            <li><a href="index.php?idp=films">Filmy</a></li>
        </ul>
    </nav>

    <!-- Główna treść strony -->
    <main>
        <?php include($strona); ?>
    </main>

    <!-- Stopka strony -->
    <footer>
        <p>&copy; 2024 Historia Lotów Kosmicznych. Wszystkie prawa zastrzeżone.</p>
    </footer>

    <!-- Skrypty animacji -->
    <script>
        $("#animacjal").on("click", function() {
            $(this).animate({
                width: "500px",
                opacity: 0.4,
                fontSize: "3em",
                borderWidth: "10px"
            }, 1500);
        });

        $("#animacja2").on({
            "mouseover": function() {
                $(this).animate({
                    width: "300px"
                }, 800);
            },
            "mouseout": function() {
                $(this).animate({
                    width: "200px"
                }, 800);
            }
        });

        $("#animacja3").on("click", function() {
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
    echo 'Autor: Wiktoria Gawryszewska, indeks: '.$nr_indeksu.', grupa: '.$nrGrupy.' <br /><br />';
    ?>
</body>
</html>
