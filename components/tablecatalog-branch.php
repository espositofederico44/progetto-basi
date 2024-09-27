<div class="container mt-5">
  <h1 class="text-primary mb-4">Visualizza Catalogo per Sede</h1>

  <!-- Form per la selezione della sede -->
  <form method="post" action="mostracatalogopersede.php">
    <div class="form-group">
      <label for="sede">Seleziona la sede:</label>
      <select name="sede" id="sede" class="form-control" required>
        <option value="">Seleziona una sede</option>
        <?php foreach ($sedi as $sede) : ?>
          <option value="<?php echo htmlspecialchars($sede['sede_id']); ?>">
            <?php echo htmlspecialchars($sede['sede_nome']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Seleziona Sede</button>
  </form>

  <!-- Mostra il nome e l'ID della sede selezionata -->
  <?php if ($selectedSede) : ?>
    <h3 class="mt-4"><span class="text-primary"><?php echo htmlspecialchars($selectedSede['sede_nome']); ?></span> (ID: <?php echo htmlspecialchars($selectedSede['sede_id']); ?>)</h3>
  <?php endif; ?>

  <!-- Mostra il messaggio se la sede non ha libri -->
  <?php if (!empty($message)) : ?>
    <div class="alert alert-warning mt-4"><?php echo $message; ?></div>
  <?php endif; ?>

  <!-- Mostra il catalogo se ci sono libri -->
  <?php if (!empty($catalog)) : ?>
    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th>ID Catalogo</th>
          <th>ISBN</th>
          <th>Titolo</th>
          <th>Disponibile</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($catalog as $book) : ?>
          <tr>
            <td><?php echo htmlspecialchars($book['catalogo_id']); ?></td>
            <td><?php echo htmlspecialchars($book['isbn']); ?></td>
            <td><?php echo htmlspecialchars($book['titolo']); ?></td>
            <td><?php echo $book['disponibile'] ? '<span class="text-success font-weight-bold">Si</span>' : '<span class="text-danger font-weight-bold">No</span>'; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
  <?php include '../components/back.php'; ?>
</div>