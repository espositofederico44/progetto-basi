<div class="container mt-5">
  <h1 class="mt-4 text-primary">Lettori con Ritardi</h1>
  <?php if (empty($lettoriConRitardi)) : ?>
    <p>Nessun lettore ha ritardi.</p>
  <?php else : ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Codice Fiscale</th>
          <th>Nome</th>
          <th>Cognome</th>
          <th>Ritardi</th>
          <th>Azione</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lettoriConRitardi as $lettore) : ?>
          <tr>
            <td><?= htmlspecialchars($lettore['cf']) ?></td>
            <td><?= htmlspecialchars($lettore['nome']) ?></td>
            <td><?= htmlspecialchars($lettore['cognome']) ?></td>
            <td><?= htmlspecialchars($lettore['ritardi']) ?></td>
            <td>
              <a href="?azzera_ritardi=<?= htmlspecialchars($lettore['cf']) ?>" class="btn btn-danger">Azzerare Ritardi</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
  <?php include '../components/back.php'; ?>
</div>