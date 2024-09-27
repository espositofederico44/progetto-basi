<div class="form-container">
  <div class="d-flex justify-content-center align-items-center mt-5">
    <div class="col-lg-7 col-sm-10 col-xs-11">
      <form method="post" class="border p-4 rounded">
        <div class="container">
          <h1 class="mb-4 text-primary">Aggiungi libro</h1>
          <p><span class="testo-campo-obbligatorio">All fields marked with an asterisk (*) are required.</span></p>

          <div class="form-group mb-3">
            <label for="isbn">ISBN:<span class="required" aria-required="true">*</span></label>
            <input type="text" id="isbn" name="isbn" class="form-control" placeholder="9788804667143" required autofocus>
          </div>

          <div class="form-group mb-3">
            <label for="titolo">Titolo:<span class="required" aria-required="true">*</span></label>
            <input type="text" class="form-control" id="titolo" name="titolo" placeholder="I promessi sposi" required autofocus>
          </div>

          <div class="form-group mb-3">
            <label for="trama">Trama:</label>
            <textarea class="form-control" rows="3" id="trama" name="trama"></textarea>
          </div>

          <div class="container d-flex justify-content-center align-items-center">
            <button type="submit" name="addbook" class="btn btn-primary">Aggiungi libro</button>
          </div>
        </div>
      </form>
      <?php if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>"; ?>
      <?php include '../components/back.php'; ?>
    </div>
  </div>
</div>