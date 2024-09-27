<div class="form-container">
  <div class="d-flex justify-content-center align-items-center mt-5">
    <div class="col-lg-7 col-sm-10 col-xs-11">
      <form method="post" class="border p-4 rounded">
        <div class="container">
          <h1 class="mb-4 text-primary">Modifica libro</h1>
          <p><span class="testo-campo-obbligatorio">All fields marked with an asterisk (*) are required.</span></p>
          <div class="form-group mb-3">
            <label for="isbn">ISBN:<span class="required" aria-required="true">*</span></label>
            <input required type="text" name="isbn" class="form-control" id="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>">
          </div>

          <div class="form-group mb-3">
            <label for="nome">Titolo:<span class="required" aria-required="true">*</span></label>
            <input required type="text" name="titolo" class="form-control" id="titolo" value="<?php echo htmlspecialchars($book['titolo']); ?>">
          </div>

          <div class="form-group mb-3">
            <label for="nome">Trama:</label>
            <textarea class="form-control" rows="3" id="trama" name="trama"><?php echo htmlspecialchars($book['trama']); ?></textarea>
          </div>


          <div class="container d-flex justify-content-center align-items-center">
            <button type="submit" name="updatebook" class="btn btn-primary">Modifica libro</button>
          </div>
        </div>
      </form>
      <?php if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>"; ?>
      <?php include '../components/back.php'; ?>
    </div>
  </div>
</div>