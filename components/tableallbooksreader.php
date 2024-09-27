<?php
// Ottieni il numero della pagina corrente
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

list($books, $totalPages, $currentPage) = fetchBooks($page);

// Inizio della tabella HTML
echo '<div class="mt-5 container">';
echo '<h1 class="mb-4 text-primary">Elenco Libri</h1>';
echo '<table class="table table-striped">';
echo '<thead><tr>';
echo '<th>ISBN</th><th>Titolo</th><th>Trama</th>';
echo '</tr></thead>';
echo '<tbody>';

// Loop sui risultati e crea le righe della tabella
foreach ($books as $row) {
  echo '<tr>';
  echo '<td>' . htmlspecialchars($row['isbn']) . '</td>'; // ISBN
  echo '<td>' . htmlspecialchars($row['titolo']) . '</td>'; // Titolo
  echo '<td>' . htmlspecialchars($row['trama']) . '</td>'; // Trama
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

echo '</div>';  // Fine container
include '../components/back.php';
