<?php
session_start();
require_once '../lib/functions.php';

if (!isset($_SESSION['user'])) {
  header("Location: ../index.php");
  exit;
}

$title = "Elenco libri";
$message = '';
