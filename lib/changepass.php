<?php
session_start();
require_once '../lib/functions.php';

if (!isset($_SESSION['user'])) {
  header("Location: ../index.php");
  exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
  $oldPassword = $_POST['old_password'];
  $newPassword = $_POST['new_password'];
  $confirmPassword = $_POST['confirm_password'];

  if ($newPassword === $confirmPassword) {
    $message = changePassword($_SESSION['user'], $oldPassword, $newPassword);
  } else {
    $message = "Le nuove password non corrispondono.";
  }
}

$title = "Cambia password";
