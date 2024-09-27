<div class="container">
  <h1 class="mt-5 text-primary">Storico Prestiti</h1>
  <div class="mt-5 container">
    <h2 class="mt-4 text-primary">Prestiti Attivi</h2>
    <?php if (!empty($prestiti_attivi)): ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ISBN</th>
            <th>Titolo</th>
            <th>Data Inizio</th>
            <th>Data Fine</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($prestiti_attivi as $prestito): ?>
            <tr>
              <td><?= htmlspecialchars($prestito['isbn']) ?></td>
              <td><?= htmlspecialchars($prestito['titolo']) ?></td>
              <td><?= htmlspecialchars($prestito['data_inizio']) ?></td>
              <td><?= htmlspecialchars($prestito['data_fine']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Non hai prestiti attivi.</p>
    <?php endif; ?>
  </div>
  <div class="mt-5 container">
    <h2 class="mt-5 text-primary">Prestiti Conclusi</h2>
    <?php if (!empty($prestiti_conclusi)): ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ISBN</th>
            <th>Titolo</th>
            <th>Data Inizio</th>
            <th>Data Fine</th>
            <th>Data Riconsegna</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($prestiti_conclusi as $prestito): ?>
            <tr>
              <td><?= htmlspecialchars($prestito['isbn']) ?></td>
              <td><?= htmlspecialchars($prestito['titolo']) ?></td>
              <td><?= htmlspecialchars($prestito['data_inizio']) ?></td>
              <td><?= htmlspecialchars($prestito['data_fine']) ?></td>
              <td><?= htmlspecialchars($prestito['data_riconsegna']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Non hai prestiti conclusi.</p>
    <?php endif; ?>
  </div>
  <?php include '../components/back.php'; ?>
</div>