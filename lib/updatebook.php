<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] == 'lettore') {
  header("Location: ../index.php");
  exit;
}

// Reindirizza se non è presente l'ISBN
if (!isset($_GET['isbn'])) {
  header("Location: elencolibri.php");
  exit;
}

$isbn = $_GET['isbn'];
$book = fetchBookByIsbn($isbn);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $message = updateBook($isbn, $_POST['titolo'], $_POST['trama']);
  $book = fetchBookByIsbn($isbn);
}

$title = "Modifica libro";
