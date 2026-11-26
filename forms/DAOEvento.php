<?php
$id = isset($_REQUEST["id"]) ? (int) $_REQUEST["id"] : 0;
$codigo = isset($_REQUEST["codigo"]) ? trim($_REQUEST["codigo"]) : "";
$nombre = isset($_REQUEST["nombre"]) ? trim($_REQUEST["nombre"]) : "";
$descripcion = isset($_REQUEST["descripcion"]) ? trim($_REQUEST["descripcion"]) : "";
$lugar = isset($_REQUEST["lugar"]) ? trim($_REQUEST["lugar"]) : "";
$fecha = isset($_REQUEST["fecha"]) ? trim($_REQUEST["fecha"]) : "";
$hora = isset($_REQUEST["hora"]) ? trim($_REQUEST["hora"]) : "";
$cupo_total = isset($_REQUEST["cupo_total"]) ? (int) $_REQUEST["cupo_total"] : 0;
// ha25004
$opcion = $_POST['opcion'] ?? $_GET['opcion'] ?? '';

if ($opcion == "guardar") {
    require_once "../config/conexion.php";

    $cupo_disponible = $cupo_total;
    $descParam = $descripcion === "" ? null : $descripcion;

    $sql = "INSERT INTO evento (codigo, nombre, descripcion, lugar, fecha, hora, cupo_total, cupo_disponible) VALUES (?,?,?,?,?,?,?,?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssii", $codigo, $nombre, $descParam, $lugar, $fecha, $hora, $cupo_total, $cupo_disponible);

    if ($stmt->execute()) {
        echo json_encode([
            "estado" => true,
            "mensaje" => "Datos Guardados Correctamente",
            "id" => $conexion->insert_id
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

    $descParam = $descripcion === "" ? null : $descripcion;

    $sql = "UPDATE evento SET codigo=?, nombre=?, descripcion=?, lugar=?, fecha=?, hora=?, cupo_total=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssssii", $codigo, $nombre, $descParam, $lugar, $fecha, $hora, $cupo_total, $id);

    if ($stmt->execute()) {
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

    $sql = "DELETE FROM evento WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

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

if ($opcion == "consultar") {
    require_once "../config/conexion.php";

    try {
        $sql = "SELECT id, codigo, nombre, descripcion, lugar, fecha, hora, cupo_total, cupo_disponible
                FROM evento
                ORDER BY fecha ASC, hora ASC";

        $resultado = $conexion->query($sql);

        if (!$resultado) {
            throw new Exception("Error al consultar");
        }

        $eventos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $eventos[] = [
                'id' => $fila['id'],
                'codigo' => $fila['codigo'],
                'nombre' => $fila['nombre'],
                'descripcion' => $fila['descripcion'],
                'lugar' => $fila['lugar'],
                'fecha' => $fila['fecha'],
                'hora' => $fila['hora'],
                'cupo_total' => $fila['cupo_total'],
                'cupo_disponible' => $fila['cupo_disponible']
            ];
        }

        echo json_encode(
            $eventos,
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

    $sql = "SELECT id, codigo, nombre, descripcion, lugar, fecha, hora, cupo_total, cupo_disponible FROM evento WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
        echo json_encode([
            "estado" => true,
            "datos" => [
                "id" => $datos["id"],
                "codigo" => $datos["codigo"],
                "nombre" => $datos["nombre"],
                "descripcion" => $datos["descripcion"],
                "lugar" => $datos["lugar"],
                "fecha" => $datos["fecha"],
                "hora" => $datos["hora"],
                "cupo_total" => $datos["cupo_total"],
                "cupo_disponible" => $datos["cupo_disponible"]
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