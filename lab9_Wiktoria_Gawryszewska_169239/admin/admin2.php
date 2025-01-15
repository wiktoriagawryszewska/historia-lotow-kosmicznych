<?php

include '../cfg.php';
session_start();

function FormularzLogowania() {
    echo '<form method="post">
            <label>Login: <input type="text" name="login"></label><br>
            <label>Hasło: <input type="password" name="password"></label><br>
            <button type="submit" name="zaloguj">Zaloguj</button>
          </form>';
}

function sprawdzLogowanie() {
    global $login, $pass;
    if (isset($_POST['zaloguj'])) {
        $wpisanyLogin = $_POST['login'] ?? '';
        $wpisaneHaslo = $_POST['password'] ?? '';
        if ($wpisanyLogin === $login && $wpisaneHaslo === $pass) {

            $_SESSION['zalogowany'] = true;
            header("Location: admin2.php");
        } else {
            echo 'Błąd logowania!<br>';
            FormularzLogowania();
        }
    }
}

function ListaPodstron() {
    global $conn;
    $query = "SELECT id, page_title FROM page_list";
    $result = mysqli_query($conn, $query);

    echo '<table>';
    echo '<tr><th>ID</th><th>Tytuł</th><th>Akcje</th></tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . htmlspecialchars($row['id']) . '</td>
                <td>' . htmlspecialchars($row['page_title']) . '</td>
                <td>
                    <form method="post" style="display:inline;">
                        <button type="submit" name="usun" value="' . $row['id'] . '">Usuń</button>
                    </form>
                    <form method="get" style="display:inline;">
                        <button type="submit" name="edytuj" value="' . $row['id'] . '">Edytuj</button>
                    </form>
                </td>
              </tr>';
    }
    echo '</table>';
}

function EdytujPodstrone() {
    global $conn;

    if (isset($_GET['edytuj'])) {
        $id = (int)$_GET['edytuj'];
        $query = "SELECT * FROM page_list WHERE id = $id";
        $result = mysqli_query($conn, $query);
        $podstrona = mysqli_fetch_assoc($result);

        if ($podstrona) {
            echo '<form method="post">
                    <label>Tytuł: <input type="text" name="page_title" value="' . htmlspecialchars($podstrona['page_title']) . '"></label><br>
                    <label>Treść: <textarea name="page_content">' . htmlspecialchars($podstrona['page_content']) . '</textarea></label><br>
                    <label>Aktywna: <input type="checkbox" name="status" ' . ($podstrona['status'] ? 'checked' : '') . '></label><br>
                    <button type="submit" name="zapisz" value="' . $id . '">Zapisz</button>
                  </form>';
        }
    }

    if (isset($_POST['zapisz'])) {
        $id = (int)$_POST['zapisz'];
        $page_title = mysqli_real_escape_string($conn, $_POST['page_title'] ?? '');
        $page_content = mysqli_real_escape_string($conn, $_POST['page_content'] ?? '');
        $status = isset($_POST['status']) ? 1 : 0;

        $query = "UPDATE page_list SET page_title = '$page_title', page_content = '$page_content', status = $status WHERE id = $id";
        mysqli_query($conn, $query);
        echo 'Podstrona zaktualizowana.<br>';
    }
}

function DodajNowaPodstrone() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj'])) {
        $page_title = mysqli_real_escape_string($conn, $_POST['page_title'] ?? '');
        $page_content = mysqli_real_escape_string($conn, $_POST['page_content'] ?? '');
        $status = isset($_POST['status']) ? 1 : 0;

        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$page_title', '$page_content', $status)";
        mysqli_query($conn, $query);
        echo 'Nowa podstrona dodana.<br>';
    }

    echo '<form method="post">
            <label>Tytuł: <input type="text" name="page_title"></label><br>
            <label>Treść: <textarea name="page_content"></textarea></label><br>
            <label>Aktywna: <input type="checkbox" name="status"></label><br>
            <button type="submit" name="dodaj">Dodaj</button>
          </form>';
}

function UsunPodstrone() {
    global $conn;

    if (isset($_POST['usun'])) {
        $id = (int)$_POST['usun'];
        $query = "DELETE FROM page_list WHERE id = $id";
        mysqli_query($conn, $query);
        echo 'Podstrona usunięta.<br>';
    }
}

function DodajKategorie() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_kategorie'])) {
        $name = mysqli_real_escape_string($conn, $_POST['nazwa'] ?? '');
        $matka = (int) ($_POST['matka'] ?? 0);

        $query = "INSERT INTO kategorie (nazwa, matka) VALUES ('$name', $matka)";
        mysqli_query($conn, $query);
        echo 'Kategoria została dodana.<br>';
    }

    echo '<form method="post">
            <label>Nazwa kategorii: <input type="text" name="nazwa"></label><br>
            <label>Kategoria nadrzędna: <select name="matka">
                    <option value="0">Brak</option>';

    $kategorie = mysqli_query($conn, "SELECT id, nazwa FROM kategorie WHERE matka = 0");
    while ($row = mysqli_fetch_assoc($kategorie)) {
        echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nazwa']) . '</option>';
    }

    echo '    </select></label><br>
            <button type="submit" name="dodaj_kategorie">Dodaj kategorię</button>
          </form>';
}

function UsunKategorie() {
    global $conn;

    if (isset($_POST['usun_kategorie'])) {
        $id = (int)$_POST['usun_kategorie'];
        $query = "DELETE FROM kategorie WHERE id = $id OR matka = $id";
        mysqli_query($conn, $query);
        echo 'Kategoria została usunięta.<br>';
    }
}

function EdytujKategorie() {
    global $conn;

    if (isset($_GET['edytuj_kategorie'])) {
        $id = (int)$_GET['edytuj_kategorie'];
        $query = "SELECT * FROM kategorie WHERE id = $id";
        $result = mysqli_query($conn, $query);
        $category = mysqli_fetch_assoc($result);

        if ($category) {
            echo '<form method="post">
                    <label>Nazwa kategorii: <input type="text" name="nazwa" value="' . htmlspecialchars($category['nazwa']) . '"></label><br>
                    <label>Kategoria nadrzędna: <select name="matka">
                            <option value="0">Brak</option>';

            $kategorie = mysqli_query($conn, "SELECT id, nazwa FROM kategorie WHERE id != $id AND matka = 0");
            while ($row = mysqli_fetch_assoc($kategorie)) {
                $selected = $row['id'] == $category['matka'] ? 'selected' : '';
                echo '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['nazwa']) . '</option>';
            }

            echo '        </select></label><br>
                    <button type="submit" name="zapisz_kategorie" value="' . $id . '">Zapisz</button>
                  </form>';
        }
    }

    if (isset($_POST['zapisz_kategorie'])) {
        $id = (int)$_POST['zapisz_kategorie'];
        $name = mysqli_real_escape_string($conn, $_POST['nazwa'] ?? '');
        $matka = (int) ($_POST['matka'] ?? 0);

        $query = "UPDATE kategorie SET nazwa = '$name', matka = $matka WHERE id = $id";
        mysqli_query($conn, $query);
        echo 'Kategoria została zaktualizowana.<br>';
    }
}

function PokazKategorie() {
    global $conn;

    $kategorie = mysqli_query($conn, "SELECT * FROM kategorie ORDER BY matka, id");
    $tree = [];

    while ($row = mysqli_fetch_assoc($kategorie)) {
        $tree[$row['matka']][] = $row;
    }

    function renderTree($matka, $tree, $level = 0) {
        if (isset($tree[$matka])) {
            foreach ($tree[$matka] as $category) {
                echo str_repeat('&nbsp;', $level * 4) . htmlspecialchars($category['nazwa']) .
                    ' <form method="post" style="display:inline;">
                        <button type="submit" name="usun_kategorie" value="' . $category['id'] . '">Usuń</button>
                    </form>
                    <a href="?edytuj_kategorie=' . $category['id'] . '">Edytuj</a><br>';

                renderTree($category['id'], $tree, $level + 1);
            }
        }
    }

    renderTree(0, $tree);
}

function DodajProdukt() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_produkt'])) {
        $tytul = mysqli_real_escape_string($conn, $_POST['tytul'] ?? '');
        $opis = mysqli_real_escape_string($conn, $_POST['opis'] ?? '');
        $data_wygasniecia = mysqli_real_escape_string($conn, $_POST['data_wygasniecia'] ?? NULL);
        $cena_netto = (float) ($_POST['cena_netto'] ?? 0);
        $podatek_vat = (float) ($_POST['podatek_vat'] ?? 0);
        $ilosc_magazyn = (int) ($_POST['ilosc_magazyn'] ?? 0);
        $status_dostepnosci = isset($_POST['status_dostepnosci']) ? 1 : 0;
        $kategoria_id = (int) ($_POST['kategoria_id'] ?? 0);
        $gabaryt = mysqli_real_escape_string($conn, $_POST['gabaryt'] ?? '');
        $zdjecie = mysqli_real_escape_string($conn, $_POST['zdjecie'] ?? '');

        $query = "INSERT INTO produkty2 (tytul, opis, data_wygasniecia, cena_netto, podatek_vat, ilosc_magazyn, status_dostepnosci, kategoria_id, gabaryt, zdjecie)
                  VALUES ('$tytul', '$opis', '$data_wygasniecia', $cena_netto, $podatek_vat, $ilosc_magazyn, $status_dostepnosci, $kategoria_id, '$gabaryt', '$zdjecie');";

        mysqli_query($conn, $query);
        echo 'Produkt został dodany.<br>';
    }

    echo '<form method="post">
            <label>Tytuł: <input type="text" name="tytul"></label><br>
            <label>Opis: <textarea name="opis"></textarea></label><br>
            <label>Data wygaśnięcia: <input type="datetime-local" name="data_wygasniecia"></label><br>
            <label>Cena netto: <input type="number" step="0.01" name="cena_netto"></label><br>
            <label>Podatek VAT (%): <input type="number" step="0.01" name="podatek_vat"></label><br>
            <label>Ilość w magazynie: <input type="number" name="ilosc_magazyn"></label><br>
            <label>Status dostępności: <input type="checkbox" name="status_dostepnosci"></label><br>
            <label>Kategoria ID: <input type="number" name="kategoria_id"></label><br>
            <label>Gabaryt: <input type="text" name="gabaryt"></label><br>
            <label>Zdjęcie (URL): <input type="text" name="zdjecie"></label><br>
            <button type="submit" name="dodaj_produkt">Dodaj produkt</button>
          </form>';
}

function PokazProdukty() {
    global $conn;

    $query = "SELECT * FROM produkty2 ORDER BY data_utworzenia DESC";
    $result = mysqli_query($conn, $query);

    echo '<table border="1">
            <tr>
                <th>ID</th>
                <th>Tytuł</th>
                <th>Opis</th>
                <th>Cena netto</th>
                <th>VAT</th>
                <th>Ilość w magazynie</th>
                <th>Status dostępności</th>
                <th>Data wygaśnięcia</th>
                <th>Akcje</th>
            </tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>
                <td>' . htmlspecialchars($row['id']) . '</td>
                <td>' . htmlspecialchars($row['tytul']) . '</td>
                <td>' . htmlspecialchars($row['opis']) . '</td>
                <td>' . htmlspecialchars($row['cena_netto']) . '</td>
                <td>' . htmlspecialchars($row['podatek_vat']) . '</td>
                <td>' . htmlspecialchars($row['ilosc_magazyn']) . '</td>
                <td>' . ($row['status_dostepnosci'] ? 'Dostępny' : 'Niedostępny') . '</td>
                <td>' . htmlspecialchars($row['data_wygasniecia']) . '</td>
                <td>
                    <form method="post" style="display:inline;">
                        <button type="submit" name="usun_produkt" value="' . $row['id'] . '">Usuń</button>
                    </form>
                    <form method="get" style="display:inline;">
                        <button type="submit" name="edytuj_produkt" value="' . $row['id'] . '">Edytuj</button>
                    </form>
                </td>
              </tr>';
    }

    echo '</table>';
}

function UsunProdukt() {
    global $conn;

    if (isset($_POST['usun_produkt'])) {
        $id = (int)$_POST['usun_produkt'];
        $query = "DELETE FROM produkty2 WHERE id = $id";
        mysqli_query($conn, $query);
        echo 'Produkt został usunięty.<br>';
    }
}

function EdytujProdukt() {
    global $conn;

    if (isset($_GET['edytuj_produkt'])) {
        $id = (int)$_GET['edytuj_produkt'];
        $query = "SELECT * FROM produkty2 WHERE id = $id";
        $result = mysqli_query($conn, $query);
        $produkt = mysqli_fetch_assoc($result);

        if ($produkt) {
            echo '<form method="post">
                    <label>Tytuł: <input type="text" name="tytul" value="' . htmlspecialchars($produkt['tytul']) . '"></label><br>
                    <label>Opis: <textarea name="opis">' . htmlspecialchars($produkt['opis']) . '</textarea></label><br>
                    <label>Data wygaśnięcia: <input type="datetime-local" name="data_wygasniecia" value="' . htmlspecialchars($produkt['data_wygasniecia']) . '"></label><br>
                    <label>Cena netto: <input type="number" step="0.01" name="cena_netto" value="' . htmlspecialchars($produkt['cena_netto']) . '"></label><br>
                    <label>Podatek VAT (%): <input type="number" step="0.01" name="podatek_vat" value="' . htmlspecialchars($produkt['podatek_vat']) . '"></label><br>
                    <label>Ilość w magazynie: <input type="number" name="ilosc_magazyn" value="' . htmlspecialchars($produkt['ilosc_magazyn']) . '"></label><br>
                    <label>Status dostępności: <input type="checkbox" name="status_dostepnosci" ' . ($produkt['status_dostepnosci'] ? 'checked' : '') . '></label><br>
                    <label>Kategoria ID: <input type="number" name="kategoria_id" value="' . htmlspecialchars($produkt['kategoria_id']) . '"></label><br>
                    <label>Gabaryt: <input type="text" name="gabaryt" value="' . htmlspecialchars($produkt['gabaryt']) . '"></label><br>
                    <label>Zdjęcie (URL): <input type="text" name="zdjecie" value="' . htmlspecialchars($produkt['zdjecie']) . '"></label><br>
                    <button type="submit" name="zapisz_produkt" value="' . $id . '">Zapisz</button>
                  </form>';
        }
    }

    if (isset($_POST['zapisz_produkt'])) {
        $id = (int)$_POST['zapisz_produkt'];
        $tytul = mysqli_real_escape_string($conn, $_POST['tytul'] ?? '');
        $opis = mysqli_real_escape_string($conn, $_POST['opis'] ?? '');
        $data_wygasniecia = mysqli_real_escape_string($conn, $_POST['data_wygasniecia'] ?? NULL);
        $cena_netto = (float) ($_POST['cena_netto'] ?? 0);
        $podatek_vat = (float) ($_POST['podatek_vat'] ?? 0);
        $ilosc_magazyn = (int) ($_POST['ilosc_magazyn'] ?? 0);
        $status_dostepnosci = isset($_POST['status_dostepnosci']) ? 1 : 0;
        $kategoria_id = (int) ($_POST['kategoria_id'] ?? 0);
        $gabaryt = mysqli_real_escape_string($conn, $_POST['gabaryt'] ?? '');
        $zdjecie = mysqli_real_escape_string($conn, $_POST['zdjecie'] ?? '');

        $query = "UPDATE produkty2 SET tytul = '$tytul', opis = '$opis', data_wygasniecia = '$data_wygasniecia', cena_netto = $cena_netto, podatek_vat = $podatek_vat, ilosc_magazyn = $ilosc_magazyn, status_dostepnosci = $status_dostepnosci, kategoria_id = $kategoria_id, gabaryt = '$gabaryt', zdjecie = '$zdjecie' WHERE id = $id";

        mysqli_query($conn, $query);
        echo 'Produkt został zaktualizowany.<br>';
    }
}


if (!isset($_SESSION['zalogowany']) || !$_SESSION['zalogowany']) {
    sprawdzLogowanie();
} else {
    echo '<a href="?lista">Lista podstron</a> | <a href="?dodaj">Dodaj nową podstronę</a> | <a href="?pokaz_kategorie">Pokaż kategorie</a> | <a href="?dodaj_kategorie">Dodaj kategorię</a> | <a href="?lista_produktow">Lista produktów</a> | <a href="?dodaj_produkt">Dodaj produkt</a> | <a href="?logout">Wyloguj</a><br>';

    if (isset($_GET['lista'])) {
        ListaPodstron();
    } elseif (isset($_GET['dodaj'])) {
        DodajNowaPodstrone();
    } elseif (isset($_GET['edytuj'])) {
        EdytujPodstrone();
    } elseif (isset($_GET['pokaz_kategorie'])) {
        PokazKategorie();
    } elseif (isset($_GET['dodaj_kategorie'])) {
        DodajKategorie();
    } elseif (isset($_GET['edytuj_kategorie'])) {
        EdytujKategorie();
    }elseif (isset($_GET['lista_produktow'])) {
        PokazProdukty();
    } elseif (isset($_GET['dodaj_produkt'])) {
        DodajProdukt();
    } elseif (isset($_GET['edytuj_produkt'])) {
        EdytujProdukt();
    } elseif (isset($_GET['logout'])) {
        session_destroy();
        header("Location: ../index.php?idp=4");
    }

    UsunPodstrone();
}
?>

