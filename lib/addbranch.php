<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['addbranch'])) {
    $cap = $_POST['cap'] ?? '';

    // Chiamata alla funzione addBranch e gestione del risultato
    $result = addBranch($_POST['nome'], $_POST['indirizzo'], $cap, $_POST['comune_id']);
    if ($result === true) {
      $message = "Sede aggiunta con successo.";
    } else {
      $message = $result;
    }
  }
}

$title = "Aggiungi sede";
