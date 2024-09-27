<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] == 'lettore') {
  header("Location: ../index.php");
  exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['addbook'])) {
    $trama = $_POST['trama'] ?? '';

    // Chiamata alla funzione addBook e controllo del risultato
    $message = addBook($_POST['isbn'], $_POST['titolo'], $trama);
  }
}

$title = "Aggiungi libro";
