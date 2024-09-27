<div class="container">
  <h2 class="mt-5 text-primary">Prestiti per Sede</h2>
  <?php if (!empty($prestiti)): ?>
    <?php
    if ($sede) {
      echo "<h4><span class=\"text-primary\">" . htmlspecialchars($sede['nome']) . "</span> (id: " . htmlspecialchars($sede['id']) . ")</h4>";
    }
    ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID prestito</th>
          <th>Titolo</th>
          <th>CF lettore</th>
          <th>Data inizio</th>
          <th>Data fine</th>
          <th>Proroga</th>
          <th>Riconsegna</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($prestiti as $prestito): ?>
          <tr>
            <td><?= htmlspecialchars($prestito['id']) ?></td>
            <td><?= htmlspecialchars($prestito['titolo']) ?></td>
            <td><?= htmlspecialchars($prestito['cf_lettore']) ?></td>
            <td><?= htmlspecialchars($prestito['data_inizio']) ?></td>
            <td><?= htmlspecialchars($prestito['data_fine']) ?></td>
            <td>
              <?php
              $data_fine = $prestito['data_fine'];
              $data_riconsegna = $prestito['data_riconsegna'];
              $today = date('Y-m-d');

              if ($data_riconsegna): ?>
                <span class="text-success">Libro già riconsegnato</span>
              <?php elseif ($today > $data_fine): ?>
                <span class="text-danger">Libro in ritardo</span>
              <?php else: ?>
                <form method="post" action="">
                  <input type="hidden" name="id_prestito" value="<?= htmlspecialchars($prestito['id']) ?>">
                  <button type="submit" name="proroga" class="btn btn-warning">+ 1 Mese</button>
                </form>
              <?php endif; ?>
            </td>
            <td>
              <?php if (!$data_riconsegna): ?>
                <form method="post" action="">
                  <input type="hidden" name="id_prestito" value="<?= htmlspecialchars($prestito['id']) ?>">
                  <button type="submit" name="riconsegna" class="btn btn-success">Riconsegna</button>
                </form>
              <?php else: ?>
                <?= htmlspecialchars($prestito['data_riconsegna']) ?>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class='alert alert-info' role='alert'>
      <p>Non ci sono prestiti registrati per questa sede.</p>
    </div>

  <?php endif; ?>
  <?php include '../components/back.php'; ?>
</div>