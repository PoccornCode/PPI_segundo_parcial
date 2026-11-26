<?php
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();

// Solo usuarios con sesión iniciada pueden registrar estudiantes
if (empty($_SESSION['autenticado'])) {
    echo json_encode(["estado" => false, "mensaje" => "Sesión no válida"], JSON_UNESCAPED_UNICODE);
    exit;
}

$carnet = trim($_POST['carnet'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');

$opcion = isset($_REQUEST["opcion"]) ? $_REQUEST["opcion"] : "";

if ($opcion == "guardar") {
    require_once "../config/conexion.php";

    try {

        if ($carnet == '' || $nombre == '' || $apellido == '' || $usuario == '') {
            throw new Exception("Todos los campos son obligatorios");
        }

        // Generar contraseña
        $clave = bin2hex(random_bytes(6));

        // Cifrar contraseña
        $claveCifrada = password_hash($clave, PASSWORD_DEFAULT);

        $sql = "INSERT INTO estudiante (carnet, nombre, apellido, usuario, clave)
                VALUES (?,?,?,?,?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param(
            "sssss",
            $carnet,
            $nombre,
            $apellido,
            $usuario,
            $claveCifrada
        );

        if ($stmt->execute()) {
            echo json_encode([
                "estado" => true,
                "mensaje" => "Datos Guardados Correctamente",
                "usuario" => $usuario,
                "clave" => $clave
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                "estado" => false,
                "mensaje" => "Error al guardar los datos"
            ], JSON_UNESCAPED_UNICODE);
        }

        $stmt->close();
        $conexion->close();

    } catch (mysqli_sql_exception $e) {

        if ($e->getCode() == 1062) {
            echo json_encode([
                "estado" => false,
                "mensaje" => "El carnet o usuario ya se encuentra registrado"
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                "estado" => false,
                "mensaje" => "Error al guardar los datos"
            ], JSON_UNESCAPED_UNICODE);
        }

    } catch (Throwable $e) {
        echo json_encode([
            "estado" => false,
            "mensaje" => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}
