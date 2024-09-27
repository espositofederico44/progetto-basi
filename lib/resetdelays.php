<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'bibliotecario') {
  header("Location: ../index.php");
  exit;
}

if (isset($_GET['azzera_ritardi'])) {
  $cf = $_GET['azzera_ritardi'];
  resetReaderDelays($cf);
  header("Location: index.php");
  exit;
}

$lettoriConRitardi = getLettoriConRitardi();

$title = "Azzera Ritardi";
