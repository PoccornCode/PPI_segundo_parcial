<?php
$servidor = "localhost";
$usuarioBD = "root";
$laclave = "";
$bbdd = "eventos_academicos";

$conexion = new mysqli($servidor, $usuarioBD, $laclave, $bbdd);

if ($conexion->connect_error) {
    die("Error de conexion:" . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
