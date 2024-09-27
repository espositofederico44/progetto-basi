<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'lettore') {
  header("Location: ../index.php");
  exit;
}

$cf = $_SESSION['user'];

// Ottenere i prestiti attivi e conclusi utilizzando le nuove funzioni
$prestiti_attivi = getActiveLoans($cf);
$prestiti_conclusi = getReturnedLoans($cf);

$title = "Storico Prestiti";
