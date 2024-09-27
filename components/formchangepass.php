  <div class="form-container">
    <div class="d-flex justify-content-center align-items-center mt-5">
      <div class="col-lg-7 col-sm-10 col-xs-11">
        <form method="post">
          <div class="container">
            <h1 class="mb-4 text-primary">Cambio Password</h1>
            <p><span class="testo-campo-obbligatorio">All fields marked with an asterisk (*) are required.</span></p>
            <div class="form-group mb-3">
              <label for="old_password">Vecchia Password:<span class="required" aria-required="true">*</span></label>
              <input class="form-control" type="password" name="old_password" required>
            </div>
            <div class="form-group mb-3">
              <label for="new_password">Nuova Password:<span class="required" aria-required="true">*</span></label>
              <input class="form-control" type="password" name="new_password" required>
            </div>
            <div class="form-group mb-3">
              <label for="confirm_password">Conferma Nuova Password:</label>
              <input class="form-control" type="password" name="confirm_password" aria-describedby="passHelp" required>
              <small id="passHelp"><em>Fai attenzione alle lettere maiuscole e minuscole quando inserisci la tua password.</em></small>
            </div>
            <div class="container d-flex justify-content-center align-items-center">
              <button type="submit" name="change_password" class="btn btn-primary">Cambia Password</button>
            </div>
          </div>
        </form>
        <?php if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>"; ?>
        <?php include '../components/back.php'; ?>
      </div>
    </div>
  </div>