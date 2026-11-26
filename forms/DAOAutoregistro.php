<?php
header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$carnet = trim($_POST['carnet'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$usuarioRegistro = trim($_POST['usuario'] ?? '');
$opcion = $_POST['opcion'] ?? '';

if ($opcion === "guardar") {
    try {
        if ($carnet === '' || $nombre === '' || $apellido === '' || $usuarioRegistro === '') {
            throw new Exception("Todos los campos son obligatorios");
        }

        require_once "../config/conexion.php";

        // Comprobar por separado los dos campos que deben ser únicos.
        $sql = "SELECT id FROM estudiante WHERE carnet = ? LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $carnet);
        $stmt->execute();
        $existe = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        if ($existe) {
            throw new Exception("El carnet ya se encuentra registrado");
        }

        $sql = "SELECT id FROM estudiante WHERE usuario = ? LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $usuarioRegistro);
        $stmt->execute();
        $existe = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        if ($existe) {
            throw new Exception("El usuario ya se encuentra registrado");
        }

        // La contraseña se genera automáticamente y se guarda cifrada.
        $clave = bin2hex(random_bytes(6));
        $claveCifrada = password_hash($clave, PASSWORD_DEFAULT);

        $sql = "INSERT INTO estudiante (carnet, nombre, apellido, usuario, clave)
                VALUES (?,?,?,?,?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssss", $carnet, $nombre, $apellido, $usuarioRegistro, $claveCifrada);
        $stmt->execute();
        $stmt->close();
        $conexion->close();

        echo json_encode([
            "estado" => true,
            "mensaje" => "Estudiante Registrado Correctamente",
            "clave" => $clave
        ], JSON_UNESCAPED_UNICODE);

    } catch (mysqli_sql_exception $e) {
        if (isset($conexion) && $conexion instanceof mysqli) {
            $conexion->close();
        }
        if ($e->getCode() == 1062) {
            echo json_encode(["estado" => false, "mensaje" => "El carnet o usuario ya se encuentra registrado"], JSON_UNESCAPED_UNICODE);
        } else {
            error_log($e->getMessage());
            echo json_encode(["estado" => false, "mensaje" => "Error al registrar los datos"], JSON_UNESCAPED_UNICODE);
        }
    } catch (Throwable $e) {
        if (isset($conexion) && $conexion instanceof mysqli) {
            $conexion->close();
        }
        echo json_encode(["estado" => false, "mensaje" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
}
