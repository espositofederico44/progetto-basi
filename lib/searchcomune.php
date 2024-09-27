<?php
session_start();
require_once '../lib/functions.php'; // Assicurati di includere la funzione dbConnect qui.


$query = $_GET['query'];
$comuni = searchComune($query);

foreach ($comuni as $comune) {
  echo '<a href="#" class="dropdown-item" onclick="selectComune(' . $comune['id'] . ', \'' . $comune['nome'] . '\')">' . $comune['nome'] . '</a>';
}
