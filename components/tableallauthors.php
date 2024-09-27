<?php
// Aggiungi codice per gestire l'eliminazione
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  deleteAuthor($id);
  header("Location: index.php"); // Reindirizza per evitare il ri-invio del form
  exit;
}

// Ottieni il numero della pagina corrente
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Richiama la funzione per ottenere gli autori
list($authors, $totalPages, $currentPage) = fetchAuthors($page);

// Inizio della tabella HTML
echo '<div class="mt-5 container">';
echo '<h1 class="mb-4 text-primary">Elenco Autori</h1>';
echo '<table class="table table-striped">';
echo '<thead><tr>';
echo '<th>Nome</th><th>Cognome</th><th>Nascita</th><th>Morte</th><th>Biografia</th><th>Modifica</th><th>Elimina</th>';
echo '</tr></thead>';
echo '<tbody>';

// Loop sui risultati e crea le righe della tabella
foreach ($authors as $row) {
  echo '<tr>';
  echo '<td>' . htmlspecialchars($row['nome']) . '</td>';
  echo '<td>' . htmlspecialchars($row['cognome']) . '</td>';
  echo '<td>' . htmlspecialchars($row['data_nascita']) . '</td>';
  echo '<td>' . htmlspecialchars($row['data_morte']) . '</td>';
  echo '<td>' . htmlspecialchars($row['biografia']) . '</td>';
  echo '<td class="td-center"><a href="modificaautore.php?id=' . $row['id'] . '" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a></td>';
  echo '<td class="td-center"><a href="?delete=' . $row['id'] . '" class="btn btn-danger"><i class="bi bi-trash"></i></a></td>';
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
include '../components/back.php';
