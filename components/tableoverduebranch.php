<div class="container">
  <h2 class="mt-4 text-primary">Ritardi sede</h2>
  <?php
  if ($sede) {
    echo "<h4><span class=\"text-primary\">" . htmlspecialchars($sede['nome']) . "</span> (id: " . htmlspecialchars($sede['id']) . ")</h4>";


    if (empty($libri_ritardo)) {
      echo '<p>Non ci sono libri in ritardo per questa sede.</p>';
    } else {
      echo '<table class="table table-bordered">';
      echo '  <thead>';
      echo '    <tr>';
      echo '      <th>ID Prestito</th>';
      echo '      <th>ISBN</th>';
      echo '      <th>Titolo</th>';
      echo '      <th>Data Inizio Prestito</th>';
      echo '      <th>Data Fine Prestito</th>';
      echo '      <th>CF Lettore</th>';
      echo '    </tr>';
      echo '  </thead>';
      echo '  <tbody>';

      // Ciclo attraverso i libri in ritardo e genera le righe della tabella
      foreach ($libri_ritardo as $libro) {
        echo '    <tr>';
        echo '      <td>' . htmlspecialchars($libro['id_prestito']) . '</td>';
        echo '      <td>' . htmlspecialchars($libro['isbn']) . '</td>';
        echo '      <td>' . htmlspecialchars($libro['titolo']) . '</td>';
        echo '      <td>' . htmlspecialchars($libro['data_inizio']) . '</td>';
        echo '      <td>' . htmlspecialchars($libro['data_fine']) . '</td>';
        echo '      <td>' . htmlspecialchars($libro['cf_lettore']) . '</td>';
        echo '    </tr>';
      }

      echo '  </tbody>';
      echo '</table>';
    }
  } else {
    echo '<div class="alert alert-info" role="alert"><p>Non hai ancora scelto la sede dove lavorare</p></div>';
  }
  ?>
  <?php include '../components/back.php'; ?>
</div>