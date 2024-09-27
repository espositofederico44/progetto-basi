<div class="form-container">
  <div class="d-flex justify-content-center align-items-center mt-5">
    <div class="col-lg-7 col-sm-10 col-xs-11">
      <form method="post">
        <div class="mt-5 container">
          <h1 class="mb-4 text-primary">Seleziona la tua sede</h1>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Selezione</th>
              </tr>
            </thead>
            <tbody>
              <?php availableBranches($cf_bibliotecario); ?>
            </tbody>
          </table>
        </div>
      </form>
      <?php if ($message): ?>
        <div class="alert alert-info" role="alert">
          <?= htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>
      <?php include '../components/back.php'; ?>
    </div>
  </div>
</div>