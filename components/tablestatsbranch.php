<div class="container">
  <h2 class="mt-5 text-primary">Statistiche sede</h2>
  <?php
  if ($sede) {
    echo "<h4><span class=\"text-primary\">" . htmlspecialchars($sede['nome']) . "</span> (id: " . htmlspecialchars($sede['id']) . ")</h4>";

    // Output della tabella con echo
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '  <tr>';
    echo '    <th>N° libri gestiti</th>';
    echo '    <th>N° ISBN gestiti</th>';
    echo '    <th>N° prestiti in corso</th>';
    echo '    <th>N° prestiti conclusi</th>';
    echo '    <th>N° totale prestiti</th>';
    echo '  </tr>';
    echo '</thead>';
    echo '<tbody>';
    echo '  <tr>';
    echo '    <td>' . htmlspecialchars($vista_dati['numero_totale_copie_gestite']) . '</td>';
    echo '    <td>' . htmlspecialchars($vista_dati['numero_totale_isbn_gestiti']) . '</td>';
    echo '    <td>' . htmlspecialchars($vista_dati['numero_prestiti_in_corso']) . '</td>';
    echo '    <td>' . htmlspecialchars($vista_dati['numero_prestiti_conclusi']) . '</td>';
    echo '    <td>' . htmlspecialchars($vista_dati['numero_totale_prestiti']) . '</td>';
    echo '  </tr>';
    echo '</tbody>';
    echo '</table>';
  } else {
    echo '<div class="alert alert-info" role="alert"><p>Non hai ancora scelto la sede dove lavorare</p></div>';
  }

  include '../components/back.php';
  ?>
</div>