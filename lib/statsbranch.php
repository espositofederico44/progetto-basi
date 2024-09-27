<?php
session_start();
require_once '../lib/functions.php';

$sede = yourBranch($_SESSION['user']);
if ($sede) {
  $vista_dati = getBranchStats($sede['id']);
}

if ($_SESSION['tipo'] != 'bibliotecario') {
  header("Location: index.php");
  exit;
}

$title = "Statistiche sede";
