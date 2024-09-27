<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'bibliotecario') {
  header("Location: ../index.php");
  exit;
}

$cf_bibliotecario = $_SESSION['user'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sede_id'])) {
  $sede_id = intval($_POST['sede_id']);
  if ($sede_id) {
    $message = linkUserBranches($cf_bibliotecario, $sede_id);
  }
}

$title = "Seleziona la tua sede";
