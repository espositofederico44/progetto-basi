<?php
require_once __DIR__ . '/../conf/config.php';

function connectDb()
{
    $connection = pg_connect("host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USERNAME . " password=" . DB_PASSWORD);
    return $connection;
}

function closeDb($db)
{
    return pg_close($db);
}

function registerUser($cf, $cognome, $nome, $tipo, $password, $category = null)
{
    $db = connectDb();

    $sql = "INSERT INTO utenti (cf, cognome, nome, tipo, password) VALUES ($1, $2, $3, $4, $5)";
    $result = pg_prepare($db, "insert_user", $sql);
    $result = pg_execute($db, "insert_user", [
        $cf,
        $cognome,
        $nome,
        $tipo,
        password_hash($password, PASSWORD_DEFAULT)
    ]);

    if (!$result) {
        echo "Errore nell'inserimento dell'utente nella tabella 'utenti': " . pg_last_error($db);
        closeDb($db);
        return false;
    }

    // Inserisce l'utente nella tabella specifica in base al tipo (es. bibliotecario, lettore)
    if ($tipo == 'bibliotecario') {
        $sql = "INSERT INTO bibliotecario (cf) VALUES ($1)";
        pg_prepare($db, "insert_bibliotecario", $sql);
        $result = pg_execute($db, "insert_bibliotecario", [$cf]);

        if (!$result) {
            echo "Errore nell'inserimento nella tabella 'bibliotecario': " . pg_last_error($db);
            closeDb($db);
            return false;
        }
    } else if ($tipo == 'lettore') {
        // Se l'utente è un lettore, assicura che la categoria sia inclusa
        if ($category === null) {
            echo "La categoria del lettore è obbligatoria.";
            closeDb($db);
            return false;
        }

        $sql = "INSERT INTO lettore (cf, categoria) VALUES ($1, $2)";
        pg_prepare($db, "insert_lettore", $sql);
        $result = pg_execute($db, "insert_lettore", [$cf, $category]);

        if (!$result) {
            echo "Errore nell'inserimento nella tabella 'lettore': " . pg_last_error($db);
            closeDb($db);
            return false;
        }
    }
    closeDb($db);
    return true;
}

function loginUser($cf, $password)
{
    session_start();
    $db = connectDb();

    $sql = "SELECT cf, password, tipo, nome, cognome FROM utenti WHERE cf = $1";
    $result = pg_prepare($db, "select_user", $sql);
    $result = pg_execute($db, "select_user", [$cf]);

    if (!$result) {
        echo "Errore nel recupero dei dati dell'utente: " . pg_last_error($db);
        closeDb($db);
        return false;
    }

    $user = pg_fetch_assoc($result);

    // Verifica se l'utente esiste e se la password è corretta
    if ($user && password_verify($password, $user['password'])) {
        // Salva i dettagli dell'utente nella session
        $_SESSION['user'] = $user['cf'];
        $_SESSION['tipo'] = $user['tipo'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['cognome'] = $user['cognome'];

        closeDb($db);

        // Reindirizza l'utente alla pagina appropriata in base al suo tipo
        if ($user['tipo'] == 'bibliotecario') {
            header("Location: bibliotecario/index.php");
        } else if ($user['tipo'] == 'lettore') {
            header("Location: lettore/index.php");
        } else if ($user['tipo'] == 'admin') {
            header("Location: admin/index.php");
        }
        exit;
    } else {
        echo "Credenziali non valide.";
        closeDb($db);
        return false;
    }
}

function changePassword($cf, $oldPassword, $newPassword)
{
    $db = connectDb();
    $sql = "SELECT password FROM utenti WHERE cf = $1";
    $result = pg_prepare($db, "get_password", $sql);
    $result = pg_execute($db, "get_password", [$cf]);

    if (!$result) {
        echo "Errore nel recupero dei dati dell'utente: " . pg_last_error($db);
        closeDb($db);
        return false;
    }

    $user = pg_fetch_assoc($result);

    // Verifica che la vecchia password corrisponda a quella salvata.
    if ($user && password_verify($oldPassword, $user['password'])) {
        // Crea un hash per la nuova password e prepara la query di aggiornamento.
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE utenti SET password = $1 WHERE cf = $2";
        $updateResult = pg_prepare($db, "update_password", $sql);
        $updateResult = pg_execute($db, "update_password", [$newPasswordHash, $cf]);

        // Verifica se l'aggiornamento è andato a buon fine.
        if (!$updateResult) {
            echo "Errore nell'aggiornamento della password: " . pg_last_error($db);
            closeDb($db);
            return false;
        }

        closeDb($db);
        return "Password aggiornata con successo.";  // Ritorna un messaggio di successo.
    } else {
        closeDb($db);
        return "La vecchia password non è corretta.";  // Ritorna un messaggio di errore se la vecchia password è sbagliata.
    }
}



function generateCard($titolo, $pagina)
{
    // Crea la struttura HTML della card, con titolo e link inclusi.
    $html = '<div class="col-sm-3 mb-3">';
    $html .= '  <div class="card">';
    $html .= '    <a href="' . htmlspecialchars($pagina) . '.php" class="stretched-link text-decoration-none">';
    $html .= '    <div class="card-body">';
    $html .= '      <h5 class="card-title">' . htmlspecialchars($titolo) . '</h5>';
    $html .= '    </div>';
    $html .= '  </a>';
    $html .= '  </div>';
    $html .= '</div>';

    return $html;  // Restituisce l'HTML completo della card.
}

function addAuthor($nome, $cognome, $biografia, $data_nascita, $data_morte)
{
    $db = connectDb();

    $data_morte = empty($data_morte) ? null : $data_morte;
    $biografia = empty($biografia) ? null : $biografia;

    $sql = "CALL add_author($1, $2, $3, $4, $5)";
    $result  = pg_prepare($db, "add_author", $sql);
    $result = pg_execute($db, "add_author", [
        $nome,
        $cognome,
        $biografia,
        $data_nascita,
        $data_morte
    ]);

    if (!$result) {
        echo "Errore durante l'aggiunta dell'autore: " . pg_last_error($db);
        closeDb($db);
        return false;
    }

    closeDb($db);
    return true;
}




function fetchAuthors($page, $limit = 15)
{
    // Connessione al database
    $db = connectDb();
    $offset = ($page - 1) * $limit;

    // Calcolo del numero totale di autori
    $totalQuery = "SELECT COUNT(*) FROM autori";
    $totalResult = pg_query($db, $totalQuery);

    if (!$totalResult) {
        echo "Errore nel calcolo del numero totale di autori: " . pg_last_error($db);
        closeDb($db);
        return [[], 0, $page]; // Restituisce array vuoto in caso di errore
    }

    $totalResults = pg_fetch_result($totalResult, 0, 0);  // Recupera il numero totale di risultati
    $totalPages = ceil($totalResults / $limit);

    // Prepara la query per recuperare gli autori
    $sql = "SELECT * FROM fetch_authors($1, $2)";
    $result = pg_prepare($db, "fetch_authors", $sql);
    $result = pg_execute($db, "fetch_authors", [$limit, $offset]);

    if (!$result) {
        echo "Errore durante il recupero degli autori: " . pg_last_error($db);
        closeDb($db);
        return [[], 0, $page]; // Restituisce array vuoto in caso di errore
    }

    $authors = pg_fetch_all($result);
    $authors = $authors ?: []; // Se non ci sono autori, restituisce array vuoto

    closeDb($db);
    return [$authors, $totalPages, $page];
}



function fetchAuthorById($id)
{
    // Connessione al database
    $db = connectDb();

    // Prepara la chiamata alla funzione PostgreSQL 'fetch_author_by_id'
    $sql = "SELECT * FROM fetch_author_by_id($1)";
    $result = pg_prepare($db, "fetch_author_by_id", $sql);
    $result = pg_execute($db, "fetch_author_by_id", [$id]);

    if (!$result) {
        echo "Errore durante il recupero dell'autore: " . pg_last_error($db);
        closeDb($db);
        return false;
    }

    $author = pg_fetch_assoc($result);

    if (!$author) {
        echo "Autore non trovato.";
        closeDb($db);
        return false;
    }

    closeDb($db);
    return $author;
}



function deleteAuthor($id)
{
    // Connessione al database
    $db = connectDb();

    // Prepara la chiamata alla procedura PostgreSQL 'delete_author_by_id'
    $sql = "CALL delete_author_by_id($1)";
    $result = pg_prepare($db, "delete_author", $sql);
    $result = pg_execute($db, "delete_author", [$id]);

    if (!$result) {
        echo "Errore durante l'eliminazione dell'autore: " . pg_last_error($db);
    } else {
        echo "Autore eliminato con successo.";
    }

    closeDb($db);
}



function updateAuthor($id, $nome, $cognome, $biografia, $data_nascita, $data_morte)
{
    $db = connectDb();

    $data_morte = empty($data_morte) ? null : $data_morte;
    $biografia = empty($biografia) ? null : $biografia;

    $sql = "CALL update_author_by_id($1, $2, $3, $4, $5, $6)";
    $result = pg_prepare($db, "update_author", $sql);
    $result = pg_execute($db, "update_author", [
        $id,
        $nome,
        $cognome,
        $biografia,
        $data_nascita,
        $data_morte
    ]);

    if (!$result) {
        $message = "Errore durante l'aggiornamento dell'autore: " . pg_last_error($db);
        closeDb($db);
        return $message; // Restituisce il messaggio di errore
    }
    $message = "Autore aggiornato";
    closeDb($db);
    return $message;
}



function addBook($isbn, $titolo, $trama)
{
    $db = connectDb();
    $trama = empty($trama) ? null : $trama;

    $sql = "CALL add_book($1, $2, $3)";
    $result = pg_prepare($db, "add_book", $sql);
    $result = pg_execute($db, "add_book", [$isbn, $titolo, $trama]);

    if (!$result) {
        $message = "Errore durante l'aggiunta del libro: " . pg_last_error($db);
        closeDb($db);
        return $message;
    }
    $message = "Libro aggiunto";
    closeDb($db);
    return  $message;
}





function fetchBooks($page, $limit = 15)
{
    $db = connectDb();
    $offset = ($page - 1) * $limit;
    $message = '';
    // Calcolo del numero totale di libri
    $totalQuery = "SELECT COUNT(*) FROM libri";
    $totalResult = pg_query($db, $totalQuery);

    $totalResults = pg_fetch_result($totalResult, 0, 0); // Numero totale di risultati
    $totalPages = ceil($totalResults / $limit);

    // Prepara ed esegue la query per ottenere i libri
    $sql = "SELECT * FROM fetch_books($1, $2)";
    $result = pg_prepare($db, "fetch_books", $sql);
    $result = pg_execute($db, "fetch_books", [$limit, $offset]);

    if (!$result) {
        $message = "Errore durante il recupero dei libri: " . pg_last_error($db);
        closeDb($db);
        return [[], 0, $page];
    }

    $books = pg_fetch_all($result) ?: []; // Restituisce un array vuoto se non ci sono libri

    closeDb($db);
    return [$books, $totalPages, $page];
}


function fetchBookByIsbn($isbn)
{
    $db = connectDb();
    $message = '';
    $sql = "SELECT * FROM fetch_book_by_isbn($1)";
    $result = pg_prepare($db, "fetch_book_by_isbn", $sql);
    $result = pg_execute($db, "fetch_book_by_isbn", [$isbn]);

    if (!$result) {
        $message = "Errore durante il recupero del libro: " . pg_last_error($db);
        closeDb($db);
        return $message;
    }
    $book = pg_fetch_assoc($result);
    closeDb($db);
    return $book ?: null;
}


function deleteBook($isbn)
{
    $db = connectDb();

    // Prepara ed esegue la query per eliminare il libro
    $sql = "CALL delete_book_by_isbn($1)";
    pg_prepare($db, "delete_book_by_isbn", $sql);
    $result = pg_execute($db, "delete_book_by_isbn", [$isbn]);

    if (!$result) {
        $message = "Errore durante l'eliminazione del libro: " . pg_last_error($db);
    } else {
        $message = "Libro eliminato con successo.";
    }

    closeDb($db);
    return $message; // Restituisce il messaggio di successo o errore
}


function updateBook($isbn, $titolo, $trama)
{
    $db = connectDb();

    $trama = empty($trama) ? null : $trama;

    $sql = "CALL update_book_by_isbn($1, $2, $3)";
    $result = pg_prepare($db, "update_book", $sql);

    $result = pg_execute($db, "update_book", [$isbn, $titolo, $trama]);


    if (!$result) {
        $message = "Errore durante l'aggiornamento del libro: " . pg_last_error($db);
        closeDb($db);
        return $message;
    }
    $message = 'Modifica effettuata con successo!';
    closeDb($db);
    return $message;
}


function addBranch($nome, $indirizzo, $cap, $comune_id)
{
    // Connessione al database
    $db = connectDb();

    // Prepara la chiamata alla procedura PostgreSQL 'add_branch'
    $sql = "CALL add_branch($1, $2, $3, $4)";
    pg_prepare($db, "add_branch", $sql);

    // Esegue la query con i parametri forniti
    $result = pg_execute($db, "add_branch", [$nome, $indirizzo, $cap, $comune_id]);

    // Verifica se la query ha avuto successo
    if (!$result) {
        $message = "Errore durante l'aggiunta della sede: " . pg_last_error($db);
        closeDb($db);
        return $message; // Restituisce il messaggio di errore
    }

    closeDb($db); // Chiude la connessione al database
    return true; // Indica che la sede è stata aggiunta con successo
}


function searchComune($query)
{
    $db = connectDb();

    $sql = "SELECT * FROM search_comune($1)";
    $result = pg_prepare($db, "search_comune", $sql);
    $result = pg_execute($db, "search_comune", [$query]);

    if (!$result) {
        echo "Errore durante la ricerca del comune: " . pg_last_error($db);
        closeDb($db);
        return [];
    }

    $comuni = pg_fetch_all($result) ?: [];

    closeDb($db);
    return $comuni;
}


function getComuneIdByName($nome)
{
    // Connessione al database
    $db = connectDb();

    // Prepara la chiamata alla funzione PostgreSQL 'get_comune_id_by_name'
    $sql = "SELECT get_comune_id_by_name($1)";
    pg_prepare($db, "get_comune_id_by_name", $sql);

    // Esegue la query con il nome del comune come parametro
    $result = pg_execute($db, "get_comune_id_by_name", [$nome]);

    if (!$result) {
        $message = "Errore durante il recupero dell'ID del comune: " . pg_last_error($db);
        closeDb($db);
        return false;
    }

    $comune_id = pg_fetch_result($result, 0, 0);

    closeDb($db);
    return $comune_id ?: false; // Restituisce l'ID del comune o false se non trovato
}


function fetchBranches()
{
    $db = connectDb();

    // Prepara ed esegue la query per ottenere le sedi
    $sql = "SELECT * FROM fetch_branches()";
    $result = pg_query($db, $sql);

    if (!$result) {
        $message = "Errore durante il recupero delle sedi: " . pg_last_error($db);
        closeDb($db);
        return []; // Restituisce un array vuoto in caso di errore
    }

    $branches = pg_fetch_all($result) ?: []; // Restituisce un array vuoto se non ci sono sedi

    closeDb($db);
    return $branches;
}

function fetchBranchById($id)
{
    $db = connectDb();

    // Prepara ed esegue la query per ottenere la sede in base all'ID
    $sql = "SELECT * FROM fetch_branch_by_id($1)";
    pg_prepare($db, "fetch_branch_by_id", $sql);
    $result = pg_execute($db, "fetch_branch_by_id", [$id]);

    if (!$result) {
        $message = "Errore durante il recupero della sede: " . pg_last_error($db);
        closeDb($db);
        return false; // Restituisce false in caso di errore
    }

    $branch = pg_fetch_assoc($result);

    closeDb($db);
    return $branch ?: null; // Restituisce null se la sede non è stata trovata
}


function updateBranch($id, $nome, $indirizzo, $cap, $comune_id)
{
    // Connessione al database
    $db = connectDb();

    // Imposta il CAP a null se è vuoto
    $cap = empty($cap) ? null : $cap;

    // Prepara la chiamata alla procedura PostgreSQL 'update_branch'
    $sql = "CALL update_branch($1, $2, $3, $4, $5)";
    pg_prepare($db, "update_branch", $sql);

    // Esegue la query passando i parametri necessari
    $result = pg_execute($db, "update_branch", [$id, $nome, $indirizzo, $cap, $comune_id]);

    closeDb($db); // Chiude la connessione al database

    return $result; // Restituisce il risultato della query per controllo nella pagina PHP
}


function deleteBranch($id)
{
    $db = connectDb();
    $message = ''; // Aggiunta del messaggio per gestire gli errori

    // Prepara ed esegue la query per eliminare la sede
    $sql = "CALL delete_branch($1)";
    pg_prepare($db, "delete_branch", $sql);
    $result = pg_execute($db, "delete_branch", [$id]);

    if (!$result) {
        $message = "Errore durante l'eliminazione della sede: " . pg_last_error($db);
    } else {
        $message = "Sede eliminata con successo.";
    }

    closeDb($db);
    return $message; // Restituisce il messaggio di successo o errore
}


function availableBranches($cf_bibliotecario)
{
    $db = connectDb();

    $sql = "SELECT * FROM available_branches($1)";
    $result = pg_prepare($db, "available_branches", $sql);
    $result = pg_execute($db, "available_branches", [$cf_bibliotecario]);

    if (!$result) {
        echo "Errore durante l'esecuzione della query: " . pg_last_error($db);
        closeDb($db);
        return;
    }

    // Loop sui risultati e crea le righe della tabella
    while ($row = pg_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['nome']) . '</td>';

        // Controllo se il bibliotecario è già assegnato a questa sede
        if (!empty($row['cf']) && $row['cf'] === $cf_bibliotecario) {
            echo '<td><em>Hai scelto questa sede</em></td>';
        } else {
            echo '<td>
                    <form method="post" action="">
                        <button type="submit" class="btn btn-primary" name="sede_id" value="' . htmlspecialchars($row['id']) . '">Scegli questa sede</button>
                    </form>
                </td>';
        }

        echo '</tr>';
    }

    closeDb($db);
}


function linkUserBranches($cf_bibliotecario, $sede_id)
{
    $db = connectDb();

    // Prepara la query per richiamare la procedura PostgreSQL
    $sql = "CALL link_user_branches($1, $2)";
    $result = pg_prepare($db, "link_user_branches", $sql);
    $result = pg_execute($db, "link_user_branches", [$cf_bibliotecario, $sede_id]);

    if (!$result) {
        $message = "Errore durante l'assegnazione della sede: " . pg_last_error($db);
    } else {
        $message = "Sede selezionata con successo!";
    }

    closeDb($db);
    return $message;
}



function collegaAutoreLibro($id_autore, $isbn_libro)
{
    $db = connectDb();
    $message = '';
    $sql = "CALL collega_autore_libro($1, $2)";
    $result = pg_prepare($db, "collega_autore_libro", $sql);
    $result = pg_execute($db, "collega_autore_libro", [$id_autore, $isbn_libro]);

    if (!$result) {
        $message = "Errore durante il collegamento autore-libro: " . pg_last_error($db);
        closeDb($db);
        return $message;
    }

    $message = "Autore e libro collegati con successo!";
    closeDb($db);
    return $message;
}

function yourBranch($cf)
{
    $db = connectDb();
    $message = '';

    $sql = "SELECT * FROM get_branch_by_cf($1)";
    $result = pg_prepare($db, "get_branch_by_cf", $sql);

    $result = pg_execute($db, "get_branch_by_cf", [$cf]);

    if (!$result) {
        $message = "Errore durante l'esecuzione della query: " . pg_last_error($db);
        closeDb($db);
        return $message;
    }

    $sede = pg_fetch_assoc($result);
    return $sede ? $sede : null;
}


function addBookInYourBranch($idSede, $isbn)
{
    $db = connectDb();
    $message = '';

    $sql = "CALL add_book_to_branch($1, $2)";
    $result = pg_prepare($db, "add_book_to_branch", $sql);

    if (!$result) {
        $message = "Errore durante la preparazione della query: " . pg_last_error($db);
        closeDb($db);
        return $message;
    }

    $result = pg_execute($db, "add_book_to_branch", [$idSede, $isbn]);

    if (!$result) {
        $message = "Errore durante l'esecuzione della query: " . pg_last_error($db);
    }

    closeDb($db);
    return $message;
}


function showCatalog($cf)
{
    $db = connectDb();
    $message = '';

    $sql = "SELECT * FROM show_catalog_by_cf($1)";
    $result = pg_prepare($db, "show_catalog", $sql);

    if (!$result) {
        $message = "Errore durante la preparazione della query: " . pg_last_error($db);
        closeDb($db);
        return $message;
    }

    $result = pg_execute($db, "show_catalog", [$cf]);

    if (!$result) {
        $message = "Errore durante l'esecuzione della query: " . pg_last_error($db);
        closeDb($db);
        return $message;
    }

    $resultati = pg_fetch_all($result);
    closeDb($db);

    return $resultati ? $resultati : [];
}


function searchBooks($title)
{
    $db = connectDb();
    $message = '';

    $sql = "SELECT * FROM search_books_by_title($1)";
    $result = pg_prepare($db, "search_books", $sql);

    $result = pg_execute($db, "search_books", [$title]);

    if (!$result) {
        $message = "Errore durante l'esecuzione della query: " . pg_last_error($db);
        closeDb($db);
        return $message;
    }

    $books = pg_fetch_all($result);
    closeDb($db);

    return $books ?: [];
}


function getAvailableBranches($isbn)
{
    $db = connectDb();
    $message = '';

    $sql = "SELECT * FROM get_available_branches_by_isbn($1)";
    $result = pg_prepare($db, "available_branches", $sql);
    $result = pg_execute($db, "available_branches", [$isbn]);

    if (!$result) {
        $message = "Errore durante l'esecuzione della query: " . pg_last_error($db);
        closeDb($db);
        return $message;
    }

    $branches = pg_fetch_all($result);
    closeDb($db);

    return $branches ?: [];
}

function isBookAvailable($id_catalogo)
{
    $db = connectDb();
    $message = '';

    $sql = "SELECT is_book_available($1)";
    $result = pg_prepare($db, "book_availability", $sql);
    $result = pg_execute($db, "book_availability", [$id_catalogo]);

    if (!$result) {
        return false;
    }

    $available = pg_fetch_result($result, 0, 0);

    return $available === 't';
}

function addLoan($cf_lettore, $id_catalogo)
{

    $db = connectDb();
    $message = '';

    // Verifico se il libro è disponibile
    if (isBookAvailable($id_catalogo) == false) {
        $message = "Il libro non è disponibile per il prestito.";
        return $message;
    }

    $data_inizio = date('Y-m-d');
    $data_fine = date('Y-m-d', strtotime('+1 month'));

    $sql = "CALL add_loan($1, $2, $3, $4)";
    $result = pg_prepare($db, "add_loan", $sql);
    $result = pg_execute($db, "add_loan", [$cf_lettore, $id_catalogo, $data_inizio, $data_fine]);

    if (!empty(pg_last_notice($db))) {
        $message = "Errore durante l'esecuzione della query: " . pg_last_notice($db);
        closeDb($db);
        return $message;
    }

    closeDb($db);
    $message = "Prestito aggiunto con successo!";
    return $message;
}

function getActiveLoans($cf_lettore)
{
    $db = connectDb();

    $sql = "SELECT * FROM get_active_loans($1)";
    $result = pg_prepare($db, "get_active_loans", $sql);
    $result = pg_execute($db, "get_active_loans", [$cf_lettore]);

    // Verifico se la query ha avuto successo
    if ($result) {
        $loans = pg_fetch_all($result);
    } else {
        $loans = [];
    }

    closeDb($db);

    return $loans ?: [];
}

function getReturnedLoans($cf_lettore)
{
    $db = connectDb();
    $sql = "SELECT * FROM get_returned_loans($1)";
    $result = pg_prepare($db, "get_returned_loans", $sql);
    $result = pg_execute($db, "get_returned_loans", [$cf_lettore]);

    // Verifica se la query ha avuto successo
    if ($result) {
        $loans = pg_fetch_all($result);
    } else {
        $loans = []; // Restituisce un array vuoto
    }

    closeDb($db);
    return $loans ?: [];
}

function getPrestitiPerSede($cf_bibliotecario)
{
    $db = connectDb();

    $sql = "SELECT * FROM get_prestiti_per_sede($1)";
    $result = pg_prepare($db, "get_prestiti_per_sede", $sql);
    $result = pg_execute($db, "get_prestiti_per_sede", [$cf_bibliotecario]);

    if ($result) {
        $loans = pg_fetch_all($result);
    } else {
        $loans = [];
    }
    return $loans ?: [];
}


function getBranchStats($sede_id)
{
    $db = connectDb();

    $sql = "SELECT * FROM vista_sede_statistiche WHERE sede = $1";
    $result = pg_prepare($db, "get_branch_stats", $sql);
    $result = pg_execute($db, "get_branch_stats", [$sede_id]);

    if (!$result) {
        echo "Errore nel recupero delle statistiche della sede: " . pg_last_error($db);
        closeDb($db);
        return false;
    }

    $stats = pg_fetch_assoc($result);

    closeDb($db);
    return $stats;
}


function getOverdueBooksByBranch($sede_id)
{
    $db = connectDb();
    $sql = "SELECT * FROM vista_libri_ritardo WHERE sede = $1";
    $result = pg_prepare($db, "get_overdue_books_by_branch", $sql);
    $result = pg_execute($db, "get_overdue_books_by_branch", [$sede_id]);

    if (!$result) {
        echo "Errore nel recupero dei libri in ritardo: " . pg_last_error($db);
        closeDb($db);
        return false;
    }

    $overdueBooks = pg_fetch_all($result);
    closeDb($db);
    return $overdueBooks;
}

function getLettoriConRitardi()
{
    $db = connectDb();
    $sql = "SELECT * FROM get_lettori_con_ritardi()";
    $result = pg_prepare($db, "get_lettori_con_ritardi", $sql);
    $result = pg_execute($db, "get_lettori_con_ritardi", []);

    if (!$result) {
        echo "Errore nel recupero dei lettori con ritardi: " . pg_last_error($db);
        closeDb($db);
        return false;
    }

    $lettoriConRitardi = pg_fetch_all($result);
    closeDb($db);
    return $lettoriConRitardi;
}

function resetReaderDelays($cf)
{
    $db = connectDb();
    $sql = "CALL azzera_ritardi_lettore($1)";
    $result = pg_prepare($db, "azzera_ritardi", $sql);
    $result = pg_execute($db, "azzera_ritardi", [$cf]);
    if (!$result) {
        echo "Errore durante l'azzeramento dei ritardi: " . pg_last_error($db);
    } else {
        echo "Ritardi azzerati con successo.";
    }
    closeDb($db);
}


function getSedi()
{
    $db = connectDb();
    $sql = "SELECT * FROM get_all_sedi()";
    $result = pg_prepare($db, "get_sedi_query", $sql);
    $result = pg_execute($db, "get_sedi_query", []);

    if (!$result) {
        echo "Errore durante l'esecuzione della query: " . pg_last_error($db);
        closeDb($db);
        return [];
    }
    $sedi = pg_fetch_all($result);
    closeDb($db);
    return $sedi ? $sedi : [];
}

function getCatalogBySede($sedeId)
{
    $db = connectDb();
    $sql = "SELECT * FROM get_catalog_by_id_sede($1)";
    $result = pg_prepare($db, "get_catalog_query", $sql);
    $result = pg_execute($db, "get_catalog_query", [$sedeId]);

    if (!$result) {
        echo "Errore durante l'esecuzione della query: " . pg_last_error($db);
        closeDb($db);
        return [];
    }

    $catalog = pg_fetch_all($result);
    closeDb($db);
    return $catalog ? $catalog : [];
}


function getSedeById($sedeId)
{
    $db = connectDb();
    $sql = "SELECT * FROM get_sede_by_id($1)";
    $result = pg_prepare($db, "get_sede_by_id_query", $sql);
    $result = pg_execute($db, "get_sede_by_id_query", [$sedeId]);

    // Verifica se l'esecuzione della query ha avuto successo
    if (!$result) {
        echo "Errore durante l'esecuzione della query: " . pg_last_error($db);
        closeDb($db);
        return false;
    }

    $sede = pg_fetch_assoc($result);
    closeDb($db);
    return $sede ? $sede : false;
}

function deleteLinkAuthorBook($id_autore, $isbn_libro)
{
    $db = connectDb();

    $sql = "CALL elimina_scritto($1, $2)";
    $result = pg_prepare($db, "elimina_scritto", $sql);
    $result = pg_execute($db, "elimina_scritto", [$id_autore, $isbn_libro]);

    if (!$result) {
        echo "Errore durante l'eliminazione della riga: " . pg_last_error($db);
        closeDb($db);
        return false;
    }

    closeDb($db);
    return true;
}

function showTableScritto()
{
    $db = connectDb();

    $sql = "SELECT * FROM get_scritto()";
    $result = pg_prepare($db, "get_scritto", $sql);
    $result = pg_execute($db, "get_scritto", []);

    if (!$result) {
        echo "Errore durante il recupero dei dati: " . pg_last_error($db);
        closeDb($db);
        return;
    }

    echo '<table class="table table-striped">';
    echo '<thead>';
    echo '<tr><th>ISBN</th><th>Titolo libro</th><th>Autore</th><th>Elimina</th></tr>';
    echo '</thead><tbody>';

    // Ciclo per creare le righe della tabella
    while ($row = pg_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['isbn']) . '</td>';
        echo '<td>' . htmlspecialchars($row['titolo']) . '</td>';
        echo '<td>' . htmlspecialchars($row['nome']) . ' ' . htmlspecialchars($row['cognome']) . '</td>';
        echo '<td>';
        echo '<form method="POST" action="">';
        echo '<input type="hidden" name="id_autore" value="' . htmlspecialchars($row['id_autore']) . '">';
        echo '<input type="hidden" name="isbn_libro" value="' . htmlspecialchars($row['isbn']) . '">';
        echo '<button type="submit" name="elimina" class="btn btn-danger btn-sm"><i class="bi bi-trash"></button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}

function getCatalogo($sede_id)
{
    $db = connectDb();
    $sql = "SELECT * FROM get_catalogo_non_raggruppato($1)";
    $result = pg_prepare($db, "get_catalogo_non_raggruppato", $sql);
    $result = pg_execute($db, "get_catalogo_non_raggruppato", [$sede_id]);

    if (!$result) {
        echo "Errore durante il recupero del catalogo: " . pg_last_error($db);
        closeDb($db);
        return [];
    }

    $catalogo = pg_fetch_all($result);
    closeDb($db);
    return $catalogo ?: [];
}

function deleteCatalogoEntry($catalogo_id)
{
    $db = connectDb();
    $sql = "CALL delete_catalogo_entry($1)";
    $result = pg_prepare($db, "delete_catalogo_entry", $sql);
    $result = pg_execute($db, "delete_catalogo_entry", [$catalogo_id]);

    if (!$result) {
        return "Errore durante l'eliminazione del libro: " . pg_last_error($db);
    }

    closeDb($db);
    return "Libro rimosso con successo dal catalogo.";
}
