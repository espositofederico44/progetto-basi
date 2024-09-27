<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'lettore') {
  header("Location: ../index.php");
  exit;
}

$message = '';
$catalog = [];
$sedi = getSedi();
$selectedSede = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sede'])) {
  $sedeId = $_POST['sede'];
  $catalog = getCatalogBySede($sedeId);
  $selectedSede = getSedeById($sedeId);

  // Verifica se la sede ha libri nel catalogo
  if (empty($catalog)) {
    $message = "La sede non ha nessun catalogo disponibile.";
  }
}

$title = "Visualizza Catalogo per Sede";
