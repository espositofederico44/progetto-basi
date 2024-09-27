<?php
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $message = deleteBranch($id);
  header("Location: index.php");
  exit;
}

$branches = fetchBranches();

// Inizio della tabella HTML
echo '<div class="mt-5 container">';
echo '<h1 class="mb-4 text-primary">Elenco Sedi</h1>';
echo '<table class="table table-striped">';
echo '<thead><tr>';
echo '<th>ID</th><th>Nome sede</th><th>Indirizzo</th><th>Cap</th><th>Modifica</th><th>Elimina</th>';
echo '</tr></thead>';
echo '<tbody>';

// Loop sui risultati e crea le righe della tabella
foreach ($branches as $row) {
  echo '<tr>';
  echo '<td>' . htmlspecialchars($row['id']) . '</td>';
  echo '<td>' . htmlspecialchars($row['nome']) . '</td>';
  echo '<td>' . htmlspecialchars($row['indirizzo']) . '</td>';
  echo '<td>' . htmlspecialchars($row['cap']) . '</td>';
  echo '<td class="td-center"><a href="modificasede.php?id=' . $row['id'] . '" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a></td>';
  echo '<td class="td-center"><a href="?delete=' . $row['id'] . '" class="btn btn-danger"><i class="bi bi-trash"></i></a></td>';
  echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';

if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>";
include '../components/back.php';
