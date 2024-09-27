<div class="form-container">
  <div class="d-flex justify-content-center align-items-center mt-5">
    <div class="col-lg-7 col-sm-10 col-xs-11">
      <form method="post" class="border p-4 rounded">
        <div class="container">
          <h1 class="mb-4 text-primary">Aggiungi Autore</h1>
          <p><span class="testo-campo-obbligatorio">All fields marked with an asterisk (*) are required.</span></p>
          <div class="form-group mb-3">
            <label for="cognome">Cognome:<span class="required" aria-required="true">*</span></label>
            <input type="text" name="cognome" class="form-control" placeholder="Rossi" id="cognome" required autofocus>
          </div>
          <div class="form-group mb-3">
            <label for="nome">Nome:<span class="required" aria-required="true">*</span></label>
            <input type="text" class="form-control" name="nome" placeholder="Mario" id="nome" required autofocus>
          </div>
          <div class="form-group mb-3">
            <label for="nome">Data di nascita:<span class="required" aria-required="true">*</span></label>
            <input type="date" class="form-control" name="data_nascita" id="data-nascita" required autofocus>
          </div>
          <div class="form-group mb-3">
            <label for="nome">Data di morte:</label>
            <input type="date" class="form-control" name="data_morte" id="data-morte">
          </div>
          <div class="form-group mb-3">
            <label for="biografia">Biografia:</label>
            <textarea class="form-control" rows="3" name="biografia" id="biografia"></textarea>
          </div>
          <div class="container d-flex justify-content-center align-items-center">
            <button type="submit" name="addauthor" class="btn btn-primary">Aggiungi autore</button>
          </div>
        </div>
      </form>
      <?php if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>"; ?>
      <?php include '../components/back.php'; ?>
    </div>
  </div>
</div>