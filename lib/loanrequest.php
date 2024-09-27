<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'lettore') {
  header("Location: ../index.php");
  exit;
}


$cf = $_SESSION['user'];
$message = "";
$branches = [];
$selectedBook = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['search'])) {
    // Chiama la funzione di ricerca libri con il titolo specificato dall'input
    $books = searchBooks($_POST['title']);
    // Verifica se la ricerca non ha prodotto risultati
    if (empty($books)) {
      $message = "Nessun libro trovato con il titolo specificato.";
    }
  }
  // Se l'utente ha selezionato un libro dalla lista
  elseif (isset($_POST['select_book'])) {
    // Salva l'ISBN del libro selezionato
    $selectedBook = $_POST['isbn'];
    // Recupera le sedi disponibili per il libro selezionato
    $branches = getAvailableBranches($selectedBook);
    // Verifica se non ci sono sedi disponibili per il libro
    if (empty($branches)) {
      $message = "Nessuna sede ha questo libro disponibile.";
    }
  } // Se l'utente ha confermato l'aggiunta del prestito
  elseif (isset($_POST['add_loan'])) {
    $message = addLoan($cf, $_POST['id_catalogo']);
  }
}

$title = "Richiedi prestito";
