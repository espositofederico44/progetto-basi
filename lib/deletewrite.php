<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] == 'lettore') {
  header("Location: ../index.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['elimina'])) {
  $id_autore = $_POST['id_autore'];
  $isbn_libro = $_POST['isbn_libro'];

  deleteLinkAuthorBook($id_autore, $isbn_libro);
}

$title = 'Elimina relazione autore e libro';
