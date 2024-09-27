<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php"); // Reindirizzare se non è admin
    exit;
}

$title = "Dashboard Admin";
require '../lib/functions.php';
require '../components/head.php';
?>

<body>

    <div class="container mt-5 mb-5 col-lg-7 col-sm-10 col-xs-11">
        <?php include '../components/dashboard.php'; ?>
        <div class="row">
            <?php echo generateCard('Cambio password', 'cambiapassword'); ?>
        </div>
        <h3>Autori</h3>
        <div class="row">
            <?php echo generateCard('Aggiungi autore', 'aggiungiautore'); ?>
            <?php echo generateCard('Visualizza autori', 'elencoautori'); ?>
        </div>
        <h3>Libri</h3>
        <div class="row">
            <?php echo generateCard('Aggiungi libro ', 'aggiungilibro'); ?>
            <?php echo generateCard('Visualizza libri', 'elencolibri'); ?>
            <?php echo generateCard('Collega autore e libro', 'collegaautorelibro'); ?>
            <?php echo generateCard('Elimina colleg. autore libro', 'eliminacollegamentoautorelibro'); ?>
        </div>
        <h3>Sedi</h3>
        <div class="row">
            <?php echo generateCard('Aggiungi sede', 'aggiungisede'); ?>
            <?php echo generateCard('Visualizza sedi', 'elencosedi'); ?>
        </div>
        <div class="container my-4 py-4">
            <?php include '../components/logout.php'; ?>
        </div>
        <?php include '../components/footer.php'; ?>
    </div>
</body>


</html>