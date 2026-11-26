
<?php
header('Content-Type: application/json; charset=utf-8');
// Que MySQL lance excepciones con el error real
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();

$opcion       = $_POST['opcion'] ?? '';
$loginUsuario = trim($_POST['loginUsuario'] ?? '');
$loginClave   = $_POST['loginClave'] ?? '';

if ($opcion === "consultar") {
    try {
        require_once "../config/conexion.php";

        if ($loginUsuario === '' || $loginClave === '') {
            throw new Exception("Ingresa tu usuario y contraseña");
        }

        $sql = "SELECT id, carnet, nombre, apellido, usuario, clave
                FROM estudiante
                WHERE usuario = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $loginUsuario);
        $stmt->execute();

        $resultado = $stmt->get_result();

        $loginCorrecto = false;
        $idEstudiante = 0;
        $nombreEstudiante = "";

        // El usuario se consulta directamente y se verifica la clave cifrada.
        // password_verify permite comprobar la contraseña sin descifrar el hash.
        if ($fila = $resultado->fetch_assoc()) {
            if (password_verify($loginClave, $fila['clave'])) {
                $loginCorrecto = true;
                $idEstudiante = $fila['id'];
                $nombreEstudiante = $fila['nombre'] . " " . $fila['apellido'];
            }
        }

        if ($loginCorrecto) {
            session_regenerate_id(true); // evita robo de sesión

            $_SESSION['autenticado'] = true;
            $_SESSION['idEstudiante'] = $idEstudiante;
            $_SESSION['nombreEstudiante'] = $nombreEstudiante;

            echo json_encode([
                "estado" => true,
                "datos" => [
                    "idEstudiante" => $idEstudiante,
                    "nombreEstudiante" => $nombreEstudiante
                ]
            ], JSON_UNESCAPED_UNICODE);
        } else {
            $_SESSION = [];

            echo json_encode([
                "estado" => false,
                "mensaje" => "Credenciales incorrectas"
            ], JSON_UNESCAPED_UNICODE);
        }

        $stmt->close();
        $conexion->close();

    } catch (Throwable $e) {
        // Mientras desarrollas se ve el error real; en producción conviene un mensaje genérico
        echo json_encode([
            "estado" => false,
            "mensaje" => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
