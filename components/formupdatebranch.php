<div class="form-container">
  <div class="d-flex justify-content-center align-items-center mt-5">
    <div class="col-lg-7 col-sm-10 col-xs-11">
      <form method="post" class="border p-4 rounded">
        <div class="container">
          <h1 class="mb-4 text-primary">Modifica sede</h1>
          <p><span class="testo-campo-obbligatorio">All fields marked with an asterisk (*) are required.</span></p>
          <div class="form-group mb-3">
            <label for="nome">Nome Sede:<span class="required" aria-required="true">*</span></label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($branch['nome']); ?>" required>
          </div>
          <div class="form-group mb-3">
            <label for="indirizzo">Indirizzo:<span class="required" aria-required="true">*</span></label>
            <input type="text" class="form-control" id="indirizzo" name="indirizzo" required value="<?php echo htmlspecialchars($branch['indirizzo']); ?>">
          </div>
          <div class="form-group mb-3">
            <label for="cap">CAP:</label>
            <input required class="form-control" type="text" id="cap" name="cap" pattern="\d{5}" value="<?php echo htmlspecialchars($branch['cap']); ?>">
          </div>
          <div class="form-group mb-3">
            <label for="comune">Comune:<span class="required" aria-required="true">*</span></label>
            <input required class="form-control" type="text" id="comune" name="comune" oninput="searchComune()" value="<?php echo htmlspecialchars($branch['comune_nome']); ?>">
            <input type="hidden" id="comune_id" name="comune_id" value="<?php echo htmlspecialchars($branch['comune_id']); ?>">
            <div id="comuneList" class="dropdown-menu"></div>
          </div>
          <div class="container d-flex justify-content-center align-items-center">
            <button type="submit" name="updatebranch" class="btn btn-primary">Modifica Sede</button>
          </div>
        </div>
      </form>

      <?php if ($message) echo "<div class='alert alert-info' role='alert'><p>$message</p></div>"; ?>

      <?php include '../components/back.php'; ?>
    </div>
  </div>
</div>

<script>
  function searchComune() {
    var comuneInput = document.getElementById('comune').value;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../lib/searchcomune.php?query=' + comuneInput, true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        var comuneList = document.getElementById('comuneList');
        comuneList.innerHTML = xhr.responseText;
        comuneList.style.display = 'block';
      }
    };
    xhr.send();
  }

  function selectComune(id, nome) {
    document.getElementById('comune').value = nome;
    document.getElementById('comune_id').value = id;
    document.getElementById('comuneList').style.display = 'none';
  }
</script>