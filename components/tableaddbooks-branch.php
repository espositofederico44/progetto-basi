<?php

$sede = yourBranch($_SESSION['user']);

if (isset($_GET['addbookinyourbranch'])) {
  $isbn = $_GET['addbookinyourbranch'];
  $message = addBookInYourBranch($sede['id'], $isbn);
}

// Ottieni il numero della pagina corrente
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

list($books, $totalPages, $currentPage) = fetchBooks($page);

// Inizio della tabella HTML
echo '<div class="mt-5 container">';
echo '<h1 class="mb-4 text-primary">Aggiungi libro alla tua sede</h1>';
if ($sede) {
  echo "<h4><span class=\"text-primary\">" . $sede['nome'] . "</span> (id: " . $sede['id'] . ")</h4>";
  echo '<table class="table table-striped">';
  echo '<thead><tr>';
  echo '<th>ISBN</th><th>Titolo</th><th>Aggiungi</th>';
  echo '</tr></thead>';
  echo '<tbody>';

  // Loop sui risultati e crea le righe della tabella
  foreach ($books as $row) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['isbn']) . '</td>'; // ISBN
    echo '<td>' . htmlspecialchars($row['titolo']) . '</td>'; // Titolo
    echo '<td class="td-center"><a href="?addbookinyourbranch=' . $row['isbn'] . '" class="btn btn-primary"><i class="bi bi-plus"></i></a></td>';
    echo '</tr>';
  }


  echo '</tbody>';
  echo '</table>';

  // Paginazione
  echo '<nav aria-label="Page navigation">';
  echo '<ul class="pagination justify-content-center">';
  for ($i = 1; $i <= $totalPages; $i++) {
    echo '<li class="page-item ' . ($currentPage === $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
  }
  echo '</ul>';
  echo '</nav>';

  echo '</div>';
} else {
  echo "<div class='alert alert-info' role='alert'><p>Non hai ancora scelto la sede dove lavorare</p></div>";
}

if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>";
include '../components/back.php';
