<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'bibliotecario') {
  header("Location: ../index.php");
  exit;
}

$cf_bibliotecario = $_SESSION['user'];
$sede = yourBranch($cf_bibliotecario);

if (!$sede) {
  echo "Errore: non è possibile recuperare la sede del bibliotecario.";
  exit;
}

$sede_id = $sede['id'];
$message = '';



// Gestione della richiesta POST per l'eliminazione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['catalogo_id'])) {
  $catalogo_id = intval($_POST['catalogo_id']);
  $message = deleteCatalogoEntry($catalogo_id);
}

// Recupera il catalogo della sede del bibliotecario
$catalogo = getCatalogo($sede_id);
$title = "Elimina libri dal catalogo";
