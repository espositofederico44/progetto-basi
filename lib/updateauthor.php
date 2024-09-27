<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] == 'lettore') {
  header("Location: ../index.php");
  exit;
}

// Reindirizza se non è presente l'ID
if (!isset($_GET['id'])) {
  header("Location: elencoautori.php");
  exit;
}

$id = $_GET['id'];
$author = fetchAuthorById($id);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $message = updateAuthor($id, $_POST['nome'], $_POST['cognome'], $_POST['biografia'], $_POST['data_nascita'], $_POST['data_morte']);;
  $author = fetchAuthorById($id);
}

$title = "Modifica autore";
