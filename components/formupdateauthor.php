<div class="form-container">
  <div class="d-flex justify-content-center align-items-center mt-5">
    <div class="col-lg-7 col-sm-10 col-xs-11">
      <form method="post" class="border p-4 rounded">
        <div class="container">
          <h1 class="mb-4 text-primary">Modifica Autore</h1>
          <p><span class="testo-campo-obbligatorio">All fields marked with an asterisk (*) are required.</span></p>
          <div class="form-group mb-3">
            <label for="nome">Nome:<span class="required" aria-required="true">*</span></label>
            <input type="text" name="nome" class="form-control" id="nome" required value="<?php echo htmlspecialchars($author['nome']); ?>">
          </div>

          <div class="form-group mb-3">
            <label for="nome">Cognome:<span class="required" aria-required="true">*</span></label>
            <input type="text" name="cognome" class="form-control" id="cognome" required value="<?php echo htmlspecialchars($author['cognome']); ?>">
          </div>

          <div class="form-group mb-3">
            <label for="nome">Data di nascita:<span class="required" aria-required="true">*</span></label>
            <input class="form-control" type="date" id="data-nascita" name="data_nascita" required value="<?php echo htmlspecialchars($author['data_nascita']); ?>">
          </div>

          <div class="form-group mb-3">
            <label for="nome">Data di morte:</label>
            <input class="form-control" type="date" id="data-morte" name="data_morte" value="<?php echo htmlspecialchars($author['data_morte']); ?>">
          </div>

          <div class="form-group mb-3">
            <label for="biografia">Biografia:</label>
            <textarea class="form-control" rows="3" id="biografia" name="biografia"><?php echo htmlspecialchars($author['biografia']); ?></textarea>
          </div>
          <div class="container d-flex justify-content-center align-items-center">
            <button type="submit" name="updateauthor" class="btn btn-primary">Modifica autore</button>
          </div>
        </div>
      </form>
      <?php if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>"; ?>
      <?php include '../components/back.php'; ?>
    </div>
  </div>
</div>