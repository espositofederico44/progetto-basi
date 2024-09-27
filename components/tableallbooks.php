<?php
if (isset($_GET['delete'])) {
  $isbn = $_GET['delete'];
  $message = deleteBook($isbn); // Assegna il messaggio di errore o successo
  header("Location: index.php");
  exit;
}

// Ottieni il numero della pagina corrente
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

list($books, $totalPages, $currentPage) = fetchBooks($page);

// Inizio della tabella HTML
echo '<div class="mt-5 container">';
echo '<h1 class="mb-4 text-primary">Elenco Libri</h1>';
echo '<table class="table table-striped">';
echo '<thead>
      <tr>';
echo '<th>ISBN</th>
        <th>Titolo</th>
        <th>Trama</th>
        <th>Modifica</th>
        <th>Elimina</th>';
echo '
      </tr>
    </thead>';
echo '<tbody>';

foreach ($books as $row) {
  echo '<tr>';
  echo '<td>' . htmlspecialchars($row['isbn']) . '</td>';
  echo '<td>' . htmlspecialchars($row['titolo']) . '</td>';
  echo '<td>' . htmlspecialchars($row['trama']) . '</td>';
  echo '<td class="td-center"><a href="modificalibro.php?isbn=' . htmlspecialchars($row['isbn']) . '" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a></td>';
  echo '<td class="td-center"><a href="?delete=' . htmlspecialchars($row['isbn']) . '" class="btn btn-danger"><i class="bi bi-trash"></i></a></td>';
  echo '</tr>';
}

echo '</tbody>';
echo '</table>';

echo '<nav aria-label="Page navigation">';
echo '<ul class="pagination justify-content-center">';
for ($i = 1; $i <= $totalPages; $i++) {
  echo '<li class="page-item ' . ($currentPage === $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
}
echo '</ul>';
echo '</nav>';

echo '</div>';
if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>";
include '../components/back.php';
