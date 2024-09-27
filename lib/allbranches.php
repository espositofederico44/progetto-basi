<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'admin') {
  header("Location: ../index.php");
  exit;
}
$message = '';
$title = "Elenco sedi";
