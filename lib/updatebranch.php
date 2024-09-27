<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

// Reindirizza se non è presente l'ID
if (!isset($_GET['id'])) {
  header("Location: elencosedi.php");
  exit;
}

$id = $_GET['id'];
$branch = fetchBranchById($id);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Esegui l'update e verifica se ha avuto successo
  $result = updateBranch($id, $_POST['nome'], $_POST['indirizzo'], $_POST['cap'], $_POST['comune_id']);

  if ($result) {
    $message = 'Modifica effettuata con successo!';
  } else {
    $message = 'Errore durante la modifica della sede.';
  }

  $branch = fetchBranchById($id);
}

$title = "Modifica sede";
