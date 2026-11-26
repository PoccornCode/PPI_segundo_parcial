<?php
session_start();

$enForms = basename(dirname($_SERVER['SCRIPT_FILENAME'])) === 'forms';
$raiz = $enForms ? '../' : '';

if (empty($_SESSION['autenticado'])) {
    header('Location: ' . $raiz . 'login.php');
    exit;
}
