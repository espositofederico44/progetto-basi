<div class="container mt-5">
  <h1 class="text-primary">Catalogo della Sede</h1>

  <?php if ($message): ?>
    <div class="alert alert-info" role="alert">
      <?= htmlspecialchars($message); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($catalogo)): ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID Catalogo</th>
          <th>ISBN</th>
          <th>Titolo</th>
          <th>Azioni</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($catalogo as $libro): ?>
          <tr>
            <td><?= htmlspecialchars($libro['catalogo_id']); ?></td>
            <td><?= htmlspecialchars($libro['isbn']); ?></td>
            <td><?= htmlspecialchars($libro['titolo']); ?></td>
            <td>
              <form method="post" action="">
                <input type="hidden" name="catalogo_id" value="<?= htmlspecialchars($libro['catalogo_id']); ?>">
                <button type="submit" class="btn btn-danger">Elimina</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>Non ci sono libri nel catalogo di questa sede.</p>
  <?php endif; ?>

  <?php include '../components/back.php'; ?>
</div>