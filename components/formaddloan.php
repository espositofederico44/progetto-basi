<!-- Fase 1: Ricerca libri -->
<div class="form-container">
  <div class="d-flex justify-content-center align-items-center mt-5">
    <div class="col-lg-7 col-sm-10 col-xs-12">
      <div class="container mb-4">
        <form method="post" class="border p-3 rounded">
          <h2 class="mb-4 text-primary">Gestione Prestiti</h2>
          <div class="form-group mb-3">
            <label for="title">Cerca un libro:</label>
            <input type="text" id="title" name="title" class="form-control mb-3" required>
            <input type="submit" class="btn btn-primary" name="search" value="Cerca">
          </div>
        </form>
      </div>

      <!-- Risultati della ricerca e selezione del libro -->
      <?php if ((isset($books) && !empty($books)) || $selectedBook) : ?>
        <div class="container mb-4">
          <form method="post" class="border p-3 rounded">
            <h2 class="mb-4 text-primary">Risultati della ricerca:</h2>
            <div class="form-group mb-3">
              <label for="isbn">Seleziona un libro dal elenco:</label>
              <select class="form-select mb-3" id="isbn" name="isbn" required>
                <?php foreach ($books as $book) : ?>
                  <option value="<?= $book['isbn'] ?>"
                    <?= ($selectedBook && $selectedBook == $book['isbn']) ? 'selected' : '' ?>>
                    <?= $book['titolo'] ?> (ISBN: <?= $book['isbn'] ?>)
                  </option>
                <?php endforeach; ?>
              </select>
              <input class="btn btn-primary" type="submit" name="select_book" value="Conferma libro">
            </div>
          </form>
        </div>
      <?php endif; ?>

      <!-- Fase 2: Selezione della sede (visibile solo se ci sono sedi disponibili) -->
      <?php if (!empty($branches)) : ?>
        <div class="container">
          <form method="post" class="border p-3 rounded">
            <h2 class="mb-4 text-primary">Sedi disponibili:</h2>
            <input type="hidden" name="isbn" value="<?= $selectedBook ?>">
            <label for="id_catalogo">Seleziona una sede disponibile:</label>
            <select class="form-select mb-3" id="id_catalogo" name="id_catalogo" required>
              <?php
              foreach ($branches as $branch) {
                echo "<option value=\"{$branch['id']}\">{$branch['nome']} - {$branch['indirizzo']} (ID Catalogo: {$branch['id']})</option>";
              }
              ?>
            </select>
            <input class="btn btn-primary" type="submit" name="add_loan" value="Aggiungi Prestito">
          </form>
        </div>
      <?php endif; ?>
      <?php if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>"; ?>
      <?php include '../components/back.php'; ?>
    </div>
  </div>
</div>