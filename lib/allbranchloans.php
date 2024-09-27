<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'bibliotecario') {
  header("Location: ../index.php");
  exit;
}

$cf_bibliotecario = $_SESSION['user'];
$sede = yourBranch($cf_bibliotecario);

// POST per proroga
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['proroga'])) {
  $id_prestito = $_POST['id_prestito'];

  $db = connectDb();

  $sql_proroga = "CALL proroga_prestito($1)";
  $result_proroga = pg_prepare($db, "proroga_prestito", $sql_proroga);
  $result_proroga = pg_execute($db, "proroga_prestito", [$id_prestito]);

  if ($result_proroga) {
    $_SESSION['message'] = "Prestito prorogato di 1 mese.";
  } else {
    $_SESSION['error'] = "Errore durante la proroga del prestito.";
  }

  closeDb($db);
}

// POST per riconsegna
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['riconsegna'])) {
  $id_prestito = $_POST['id_prestito'];
  $data_riconsegna = date('Y-m-d');
  $db = connectDb();

  $sql_riconsegna = "CALL riconsegna_prestito($1, $2)";
  $result_riconsegna = pg_prepare($db, "riconsegna_prestito", $sql_riconsegna);
  $result_riconsegna = pg_execute($db, "riconsegna_prestito", [$id_prestito, $data_riconsegna]);

  if ($result_riconsegna) {
    // Successo nella riconsegna, ora controlla il ritardo e aggiorna
    $sql_controlla_ritardo = "CALL controlla_ritardo_e_aggiorna($1)";
    $result_controlla_ritardo = pg_prepare($db, "controlla_ritardo_e_aggiorna", $sql_controlla_ritardo);
    $result_controlla_ritardo = pg_execute($db, "controlla_ritardo_e_aggiorna", [$id_prestito]);

    if ($result_controlla_ritardo) {
      $_SESSION['message'] = "Prestito riconsegnato correttamente e stato di ritardo aggiornato.";
    } else {
      $_SESSION['error'] = "Errore durante l'aggiornamento del ritardo.";
    }
  } else {
    $_SESSION['error'] = "Errore durante la riconsegna del prestito.";
  }

  closeDb($db);
}



// Ottenere i prestiti per la sede del bibliotecario
$prestiti = getPrestitiPerSede($cf_bibliotecario);

$title = "Gestisci prestiti";
