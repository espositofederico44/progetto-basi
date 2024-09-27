<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'lettore') {
    header("Location: ../index.php"); // Reindirizzare se non è admin
    exit;
}

$title = "Dashboard Lettore";
require '../lib/functions.php';
require '../components/head.php';
?>

<body>

    <div class="container mt-5 mb-5 col-lg-7 col-sm-10 col-xs-11">
        <?php include '../components/dashboard.php'; ?>
        <div class="row">
            <?php echo generateCard('Cambio password', 'cambiapassword'); ?>
        </div>
        <h3>Libri</h3>
        <div class="row">
            <?php echo generateCard('Visualizza libri', 'elencolibri'); ?>
            <?php echo generateCard('Catalogo sedi', 'mostracatalogopersede'); ?>
        </div>
        <h3>Prestiti</h3>
        <div class="row">
            <?php echo generateCard('Richiedi un prestito', 'richiediprestito'); ?>
            <?php echo generateCard('Storico prestiti', 'storicoprestiti'); ?>
        </div>
        <div class="container my-4 py-4">
            <?php include '../components/logout.php'; ?>
        </div>
        <?php include '../components/footer.php'; ?>
    </div>
</body>


</html>