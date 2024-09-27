<?php
// Chiama la funzione ShowCatalog per ottenere i risultati
$results = showCatalog($_SESSION['user']);
$sede = yourBranch($_SESSION['user']);
$message = '';

echo '<div class="mt-5 container">';
echo '<h1 class="mb-4 text-primary">Catalogo della sede</h1>';
if ($sede) {
  echo "<h4><span class=\"text-primary\">" . $sede['nome'] . "</span> (id: " . $sede['id'] . ")</h4>";
  if (is_array($results)) {
    if (!empty($results)) {
      echo "<table class=\"table table-striped\">";
      echo "<thead><tr><th>Titolo</th><th>ISBN</th><th>Numero di Copie</th><th>Copie Disponibili</th></tr></thead>";
      echo '<tbody>';
      foreach ($results as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['titolo']) . "</td>";
        echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
        echo "<td>" . htmlspecialchars($row['num_copie']) . "</td>";
        echo "<td>" . htmlspecialchars($row['disponibili']) . "</td>";
        echo "</tr>";
      }
      echo '</tbody>';
      echo "</table>";
    } else {
      echo "<p>Nessun libro trovato.</p>";
    }
  } else {
    // Se showCatalog ha restituito un errore
    echo $results;
  }
} else {
  echo "<div class='alert alert-info' role='alert'><p>Non hai ancora scelto la sede dove lavorare</p></div>";
}

echo '</div>';
if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>";
include '../components/back.php';
