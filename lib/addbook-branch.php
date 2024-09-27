<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'bibliotecario') {
  header("Location: ../index.php");
  exit;
}

$title = "Aggiungi libro alla sede";
$message = '';
