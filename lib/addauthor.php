<?php
session_start();
require_once '../lib/functions.php';

// Verifica il tipo di utente (solo admin e bibliotecari possono accedere)
if ($_SESSION['tipo'] == 'lettore') {
  header("Location: ../index.php");
  exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['addauthor'])) {
    $biografia = $_POST['biografia'] ?? '';
    $data_morte = $_POST['data_morte'] ?? '';

    // Chiamata alla funzione addAuthor e controllo del risultato
    $result = addAuthor($_POST['cognome'], $_POST['nome'], $biografia, $_POST['data_nascita'], $data_morte);
    if ($result === true) {
      $message = "Autore aggiunto con successo.";
    } else {
      // Gestisce l'errore ricevuto da addAuthor
      $message = $result;
    }
  }
}

$title = "Aggiungi autore";
