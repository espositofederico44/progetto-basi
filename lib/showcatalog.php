<?php
session_start();
require_once '../lib/functions.php';

if ($_SESSION['tipo'] != 'bibliotecario') {
  header("Location: ../index.php");
  exit;
}


$title = "Mostra il catalogo della sede del bibliotecario corrente";
