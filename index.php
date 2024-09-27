<?php
session_start(); // Avvia la sessione PHP per gestire gli utenti autenticati
require_once 'lib/functions.php'; // Include le funzioni di utilità

// Controlla se l'utente è già loggato
if (isset($_SESSION['user']) && isset($_SESSION['tipo'])) {
  // Reindirizza l'utente alla pagina corretta in base al suo tipo
  if ($_SESSION['tipo'] == 'bibliotecario') {
    header("Location: bibliotecario/index.php");
    exit;
  } elseif ($_SESSION['tipo'] == 'lettore') {
    header("Location: lettore/index.php");
    exit;
  } elseif ($_SESSION['tipo'] == 'admin') {
    header("Location: admin/index.php");
    exit;
  }
}

// Gestione delle richieste POST (login o registrazione)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['login'])) {
    loginUser($_POST['cf'], $_POST['password']);
  } elseif (isset($_POST['register'])) {
    $category = isset($_POST['category']) ? $_POST['category'] : null;

    registerUser($_POST['cf'], $_POST['cognome'], $_POST['nome'], $_POST['tipo'], $_POST['password'], $category);
  }
}

$title = "Login/Registrazione"; // Imposta il titolo della pagina
require 'components/head.php'; // Include il file per la sezione <head> della pagina
?>

<style>
  .form-container {
    display: none;
    /* Nascondi i form per il toggle */
  }

  #loginForm {
    display: block;
    /* Visualizza il form di login inizialmente */
  }
</style>

<script>
  // Funzione per alternare tra il form di login e il form di registrazione
  function toggleForms() {
    var loginForm = document.getElementById('loginForm');
    var registerForm = document.getElementById('registerForm');
    if (loginForm.style.display === 'block') {
      loginForm.style.display = 'none';
      registerForm.style.display = 'block';
    } else {
      loginForm.style.display = 'block';
      registerForm.style.display = 'none';
    }
  }

  // Mostra il campo categoria solo se l'utente è un lettore
  function toggleCategory() {
    var selectTipo = document.getElementById('tipo');
    var categoryField = document.getElementById('categoryField');
    categoryField.style.display = (selectTipo.value === 'lettore') ? 'block' : 'none';
  }
</script>
</head>

<body>
  <!-- Form di login -->
  <div id="loginForm" class="form-container">
    <div class="d-flex justify-content-center align-items-center mt-5">
      <div class="col-lg-7 col-sm-10 col-xs-11">
        <form method="post" class="border p-4 rounded">
          <div class="container">
            <h1 class="mb-4 text-primary">Login</h1>
            <p><span class="testo-campo-obbligatorio">All fields marked with an asterisk (*) are required.</span></p>
            <div class="form-group mb-3">
              <label for="cf">Codice Fiscale:<span class="required" aria-required="true">*</span></label>
              <input class="form-control" type="text" name="cf" placeholder="RSSMRA80L05F593A" required autofocus>
            </div>
            <div class="form-group mb-3">
              <label for="password">Password:<span class="required" aria-required="true">*</span></label>
              <input class="form-control" aria-describedby="passHelp" type="password" name="password" placeholder="************" required>
              <small id="passHelp"><em>Fai attenzione alle lettere maiuscole e minuscole quando inserisci la tua password.</em></small>
            </div>
            <div class="container d-flex justify-content-center align-items-center">
              <button type="submit" name="login" class="btn btn-primary">Login</button>
            </div>
          </div>
        </form>
        <div class="container mt-5">
          <p class="text-center" onclick="toggleForms()">Devi creare il tuo account? <span class="collegamento">Registrati qui</span></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Form di registrazione -->
  <div id="registerForm" class="form-container">
    <div class="d-flex justify-content-center align-items-center mt-5">
      <div class="col-lg-7 col-sm-10 col-xs-11">
        <form method="post" class="border p-4 rounded">
          <div class="container">
            <h1 class="mb-4 text-primary">Registrazione</h1>
            <p><span class="testo-campo-obbligatorio">All fields marked with an asterisk (*) are required.</span></p>
            <div class="form-group mb-3">
              <label for="cf">Codice Fiscale:<span class="required" aria-required="true">*</span></label>
              <input type="text" class="form-control" name="cf" id="cf" placeholder="RSSMRA80L05F593A" required autofocus>
            </div>
            <div class="form-group mb-3">
              <label for="cognome">Cognome:<span class="required" aria-required="true">*</span></label>
              <input type="text" name="cognome" class="form-control" placeholder="Rossi" id="cognome" required autofocus>
            </div>
            <div class="form-group mb-3">
              <label for="nome">Nome:<span class="required" aria-required="true">*</span></label>
              <input type="text" class="form-control" name="nome" placeholder="Mario" id="nome" required autofocus>
            </div>
            <div class="form-group mb-3">
              <label for="password">Password:<span class="required" aria-required="true">*</span></label>
              <input type="password" name="password" class="form-control" id="password" aria-describedby="passHelp" required autofocus>
              <small id="passHelp"><em>Fai attenzione alle lettere maiuscole e minuscole quando inserisci la tua password.</em></small>
            </div>

            <div class="form-group mb-3">
              <label for="tipo">Indica se sei un bibliotecario o un lettore.<span class="required" aria-required="true">*</span></label>
              <select class="form-select" name="tipo" id="tipo" required onchange="toggleCategory()" aria-label="Tipo di utente">
                <option value="bibliotecario" selected>Bibliotecario</option>
                <option value="lettore">Lettore</option>
              </select>
            </div>
            <div id="categoryField" class="form-group mb-3" style="display:none;">
              <p>Indica la categoria del lettore<span class="required" aria-required="true">*</span></p>
              <input class="form-check-input mb-3" type="radio" name="category" value="base" id="base" checked>
              <label class="form-check-label" for="base">Base</label>
              <br>
              <input class="form-check-input" type="radio" name="category" value="premium" id="premium">
              <label class="form-check-label" for="premium">Premium</label>
            </div>

            <div class="container d-flex justify-content-center align-items-center">
              <button type="submit" name="register" class="btn btn-primary">Registrati</button>
            </div>
          </div>
        </form>
        <div class="container mt-5">
          <p class="text-center" onclick="toggleForms()">Utente già registrato? <span class="collegamento">Accedi da qui</span></p>
        </div>
      </div>
    </div>
  </div>

  <?php
  include 'components/footer.php';
  ?>
</body>

</html>