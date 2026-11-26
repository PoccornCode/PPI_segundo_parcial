<?php
$nombre = isset($_REQUEST["nombre"]) ? $_REQUEST["nombre"] : "";
$descripcion = isset($_REQUEST["descripcion"]) ? $_REQUEST["descripcion"] : "";
$idruta = $_REQUEST["idruta"]??'';

$opcion = $_POST['opcion']??'';

if ($opcion == "guardar") {
    require_once "../config/conexion.php";

    //$clave = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO ruta (nombre, descripcion) VALUES(?,?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss",$nombre, $descripcion);
    if ($stmt->execute()) {
        echo json_encode([
            "estado" => true,
            "mensaje" => "Datos Guardados Correctamente"
        ]);
    } else {
        echo json_encode([
            "estado" => false,
            "mensaje" => "Error al guardar los datos"
        ]);
    }
    $stmt->close();
    $conexion->close();
}
if ($opcion == "Modificar") {
    require_once "../config/conexion.php";
    
    $sql = "UPDATE ruta SET nombre=?,descripcion=? WHERE idruta=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $descripcion, $idruta);
    if ($stmt->execute()) {
    // if (1>0) {
        echo json_encode([
            "estado" => true,
            "mensaje" => "Datos Modificados Correctamente"
        ]);
    } else {
        echo json_encode([
            "estado" => false,
            "mensaje" => "Error al modificar los datos"
        ]);
    }
    $stmt->close();
    $conexion->close();
}
if ($opcion == "eliminar") {
    
    require_once "../config/conexion.php";

    $sql = "DELETE FROM ruta WHERE idruta=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idruta);
    if ($stmt->execute()) {
        echo json_encode([
            "estado" => true,
            "mensaje" => "Datos Eliminados Correctamente"
        ]);
    } else {
        echo json_encode([
            "estado" => false,
            "mensaje" => "Error al eliminar los datos"
        ]);
    }
    $stmt->close();
    $conexion->close();
}

// if ($opcion == "consultarDUI") {
//     require_once "../config/conexion.php";

//     $sql = "SELECT dui FROM empleado WHERE dui=?";
//     $stmt = $conexion->prepare($sql);
//     $stmt->bind_param("s", $dui);

//     $stmt->execute();

//     $resultado = $stmt->get_result();

//     if ($resultado->num_rows > 0) {

//         echo json_encode([
//             "estado" => true,
//             "mensaje" => "Este Dui ya existe"
//         ]);
//     } else {
//         echo json_encode([
//             "estado" => false,
//             "mensaje" => "a"
//         ]);
//     }

//     $stmt->close();
//     $conexion->close();
// }

if ($opcion == "consultar") {
    require_once "../config/conexion.php";

    try {
        $sql = "SELECT idruta,nombre,descripcion
                FROM ruta
                ORDER BY nombre";

        $resultado = $conexion->query($sql);

        if (!$resultado) {
            throw new Exception("Error al consultar");
        }

        $rutas = [];

        while ($fila = $resultado->fetch_assoc()) {
            //$rutas[] = $fila; //este sirve para guardar de una

            //este otro sirve para poder hacer calculos entre la consulta y luego retonar y guardar eso
            $rutas[] = [
                'idruta' => $fila['idruta'],
                'nombre' => $fila['nombre'],
                'descripcion' => $fila['descripcion']
            ];
        }

        echo json_encode(
            $rutas,
            JSON_UNESCAPED_UNICODE
        );

        $resultado->free();
        $conexion->close();
    } catch (Throwable $e) {

        echo json_encode([
            "mensaje" => "No se pudieron obtener los registros"
        ], JSON_UNESCAPED_UNICODE);
    }
}
if ($opcion == "consultarIndividual") {
    require_once "../config/conexion.php";

    $sql = "SELECT idruta,nombre,descripcion FROM ruta WHERE idruta=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idruta);

    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
        echo json_encode([
            "estado" => true,
            "datos" => [
                "idruta" => $datos["idruta"],
                "nombre" => $datos["nombre"],
                "descripcion" => $datos["descripcion"]
            ]
        ]);
    } else {
        echo json_encode([
            "estado" => false,
            "datos" => "No Existe"
        ]);
    }

    $stmt->close();
    $conexion->close();
}
