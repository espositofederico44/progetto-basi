<?php
session_start();
require_once '../lib/functions.php';
$pageBooks = isset($_GET['page_books']) ? (int)$_GET['page_books'] : 1;
$pageAuthors = isset($_GET['page_authors']) ? (int)$_GET['page_authors'] : 1;

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_autore = $_POST['id_autore'] ?? null;
  $isbn_libro = $_POST['isbn_libro'] ?? null;

  if ($id_autore && $isbn_libro) {
    $message = collegaAutoreLibro($id_autore, $isbn_libro);
  } else {
    $message = "Per favore seleziona sia un autore che un libro.";
  }
}

list($books, $totalPagesBooks, $currentPageBooks) = fetchBooks($pageBooks, 15);
list($authors, $totalPagesAuthors, $currentPageAuthors) = fetchAuthors($pageAuthors, 15);


$title = "Collega autore a libro";
