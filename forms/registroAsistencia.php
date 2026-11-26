<?php
session_start();

$servidor = "localhost";
$usuario  = "root";
$laclave  = "";
$bbdd     = "sys_agua";

$conexion = new mysqli($servidor, $usuario, $laclave, $bbdd);
if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

function h($valor)
{
    return htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function responderAsistencia($codigo, $resultado)
{
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        responderAsistencia(403, ['estado' => false, 'mensaje' => 'Solicitud inválida o vencida.']);
    }

    $opcion = $_POST['opcion'] ?? '';

    try {
        if ($opcion === 'consultarEventos') {
            $res = $conexion->query("SELECT id, codigo, nombre, fecha FROM evento ORDER BY fecha ASC, hora ASC");
            if (!$res) {
                throw new Exception("Error al consultar eventos: " . $conexion->error);
            }

            $eventos = [];
            while ($fila = $res->fetch_assoc()) {
                $eventos[] = $fila;
            }
            $res->free();

            responderAsistencia(200, ['estado' => true, 'datos' => $eventos]);
        }

        if ($opcion === 'consultarInscritos') {
            $eventoId = filter_var($_POST['evento_id'] ?? 0, FILTER_VALIDATE_INT);
            if ($eventoId === false || $eventoId < 0) {
                responderAsistencia(400, ['estado' => false, 'mensaje' => 'Evento inválido.']);
            }

            $sql = "SELECT
                        i.id AS inscripcion_id,
                        i.estudiante_id,
                        i.evento_id,
                        e.carnet,
                        e.nombre,
                        e.apellido,
                        ev.nombre AS evento,
                        ev.fecha,
                        i.estado_asistencia,
                        i.fecha_asistencia
                    FROM inscripcion i
                    INNER JOIN estudiante e ON e.id = i.estudiante_id
                    INNER JOIN evento ev ON ev.id = i.evento_id
                    WHERE (? = 1 OR ev.id = ?)
                    ORDER BY ev.fecha ASC, e.apellido ASC, e.nombre ASC";

            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $conexion->error);
            }

            $todos = $eventoId === 0 ? 1 : 0;
            $stmt->bind_param("ii", $todos, $eventoId);
            $stmt->execute();
            $res = $stmt->get_result();

            $inscritos = [];
            while ($fila = $res->fetch_assoc()) {
                $inscritos[] = $fila;
            }

            $stmt->close();
            responderAsistencia(200, ['estado' => true, 'datos' => $inscritos]);
        }

        if ($opcion === 'guardar') {
            $inscripcionId = filter_var($_POST['inscripcion_id'] ?? null, FILTER_VALIDATE_INT);
            $estadoAsistencia = strtoupper(trim((string) ($_POST['estado_asistencia'] ?? '')));
            $estadosValidos = ['PENDIENTE', 'ASISTIO', 'AUSENTE', 'JUSTIFICADO'];

            if (!$inscripcionId || !in_array($estadoAsistencia, $estadosValidos, true)) {
                responderAsistencia(400, [
                    'estado' => false,
                    'mensaje' => 'Revise los datos de asistencia.'
                ]);
            }

            // Verifica que exista la inscripción
            $stmtCheck = $conexion->prepare("SELECT id FROM inscripcion WHERE id = ? LIMIT 1");
            $stmtCheck->bind_param("i", $inscripcionId);
            $stmtCheck->execute();
            $resCheck = $stmtCheck->get_result();
            if ($resCheck->num_rows === 0) {
                $stmtCheck->close();
                responderAsistencia(404, [
                    'estado' => false,
                    'mensaje' => 'No existe la inscripción seleccionada.'
                ]);
            }
            $stmtCheck->close();

            // El trigger trg_inscripcion_before_update ajusta fecha_asistencia automáticamente
            $sqlUpd = "UPDATE inscripcion SET estado_asistencia = ? WHERE id = ?";
            $stmtUpd = $conexion->prepare($sqlUpd);
            if (!$stmtUpd) {
                throw new Exception("Error al preparar actualización: " . $conexion->error);
            }

            $stmtUpd->bind_param("si", $estadoAsistencia, $inscripcionId);
            if (!$stmtUpd->execute()) {
                $err = $stmtUpd->error;
                $stmtUpd->close();
                throw new Exception("Error al guardar asistencia: " . $err);
            }
            $stmtUpd->close();

            responderAsistencia(200, ['estado' => true, 'mensaje' => 'Asistencia guardada.']);
        }

        responderAsistencia(400, ['estado' => false, 'mensaje' => 'Opción no válida.']);
    } catch (Throwable $error) {
        error_log('Registro de asistencia: ' . $error->getMessage());
        responderAsistencia(500, [
            'estado' => false,
            'mensaje' => 'No se pudo procesar la asistencia.'
        ]);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="csrf-token" content="<?= h($_SESSION['csrf_token']) ?>">
    <title>Sistema de eventos universitarios | Asistencia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.3.0/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/styles/overlayscrollbars.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family:"Source Sans 3",Arial,sans-serif; }
        .attendance-empty { padding:2rem !important; color:var(--bs-secondary-color); text-align:center; }
        .attendance-form { display:flex; align-items:center; gap:0.5rem; margin:0; }
        .attendance-count { min-width:8rem; }
    </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <header class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Mostrar u ocultar menú">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link" href="#">Eventos Universitarios</a>
                </li>
            </ul>
        </div>
    </header>

    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a class="brand-link" href="#">
                <span class="brand-text fw-light">Eventos Universitarios</span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2" aria-label="Navegación principal">
                <ul class="nav sidebar-menu flex-column" role="menu" data-lte-toggle="treeview" data-accordion="false">
                    <li class="nav-item">
                        <a href="registro_evento.php" class="nav-link">
                            <i class="nav-icon bi bi-calendar-plus"></i><p>Registro de evento</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="lista-evento.php" class="nav-link">
                            <i class="nav-icon bi bi-calendar-event"></i><p>Lista de eventos</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="inscripcion.php" class="nav-link">
                            <i class="nav-icon bi bi-card-checklist"></i><p>Inscripciones</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="registroAsistencia.php" class="nav-link active">
                            <i class="nav-icon bi bi-clipboard-check"></i><p>Asistencia</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6"><h3 class="mb-0">Control académico</h3></div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                            <li class="breadcrumb-item active">Registro de asistencia</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                    <div>
                        <h1 class="mb-0">Registro de asistencia</h1>
                        <p class="text-body-secondary mb-0">Indica el estado de asistencia de los estudiantes inscritos.</p>
                    </div>
                    <div class="text-end">
                        <span id="totalInscritos" class="badge text-bg-primary fs-6 attendance-count">0 inscritos</span>
                    </div>
                </div>

                <section class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-clipboard-check me-2"></i>Estudiantes inscritos</h3>
                        <div class="card-tools d-flex align-items-center gap-2">
                            <label for="filtroEvento" class="visually-hidden">Filtrar por evento</label>
                            <select id="filtroEvento" class="form-select form-select-sm">
                                <option value="0">Todos los eventos</option>
                            </select>
                            <button id="btnRecargar" type="button" class="btn btn-tool" aria-label="Recargar lista">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>Carnet</th>
                                    <th>Estudiante</th>
                                    <th>Evento</th>
                                    <th>Fecha evento</th>
                                    <th>Fecha asistencia</th>
                                    <th>Asistencia</th>
                                </tr>
                                </thead>
                                <tbody id="tablaAsistencia">
                                <tr><td colspan="6" class="attendance-empty">Cargando inscripciones...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-body-secondary">
                        Selecciona el estado de asistencia y guarda cada registro.
                    </div>
                </section>
            </div>
        </div>
    </main>

    <footer class="app-footer"><strong>Sistema de eventos académicos</strong></footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/browser/overlayscrollbars.browser.es6.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const filtroEvento = document.getElementById('filtroEvento');
    const tabla = document.getElementById('tablaAsistencia');
    const total = document.getElementById('totalInscritos');
    const botonRecargar = document.getElementById('btnRecargar');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const escapar = valor => String(valor ?? '').replace(/[&<>"']/g, caracter => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[caracter]);

    async function llamarServidor(opcion, campos = {}) {
        const datos = new URLSearchParams({ opcion, ...campos, csrf_token: csrfToken });
        const respuesta = await fetch('registroAsistencia.php', {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();

        if (!respuesta.ok || !resultado.estado) {
            throw new Error(resultado.mensaje || `Error HTTP ${respuesta.status}`);
        }
        return resultado;
    }

    async function cargarEventos() {
        const resultado = await llamarServidor('consultarEventos');
        resultado.datos.forEach(evento => {
            const opcion = document.createElement('option');
            opcion.value = evento.id;
            opcion.textContent = `${evento.nombre} (${evento.fecha})`;
            filtroEvento.appendChild(opcion);
        });
    }

    async function cargarInscritos() {
        tabla.innerHTML = '<tr><td colspan="6" class="attendance-empty">Cargando inscripciones...</td></tr>';
        const resultado = await llamarServidor('consultarInscritos', {
            evento_id: filtroEvento.value
        });
        total.textContent = `${resultado.datos.length} inscritos`;
        tabla.innerHTML = '';

        const estados = ['PENDIENTE', 'ASISTIO', 'AUSENTE', 'JUSTIFICADO'];

        resultado.datos.forEach(fila => {
            const opcionesHtml = estados.map(st =>
                `<option value="${st}" ${fila.estado_asistencia === st ? 'selected' : ''}>${st}</option>`
            ).join('');

            const registro = document.createElement('tr');
            registro.innerHTML = `
                <td>${escapar(fila.carnet)}</td>
                <td>${escapar(fila.nombre)} ${escapar(fila.apellido)}</td>
                <td>${escapar(fila.evento)}</td>
                <td>${escapar(fila.fecha)}</td>
                <td>${escapar(fila.fecha_asistencia || '—')}</td>
                <td>
                    <form class="attendance-form">
                        <input type="hidden" name="inscripcion_id" value="${Number(fila.inscripcion_id)}">
                        <select name="estado_asistencia" class="form-select form-select-sm" style="width: auto;">
                            ${opcionesHtml}
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-save me-1"></i> Guardar
                        </button>
                    </form>
                </td>`;
            tabla.appendChild(registro);
        });

        if (resultado.datos.length === 0) {
            tabla.innerHTML = '<tr><td colspan="6" class="attendance-empty">No hay estudiantes inscritos.</td></tr>';
        }
    }

    tabla.addEventListener('submit', async evento => {
        const formulario = evento.target.closest('.attendance-form');
        if (!formulario) return;
        evento.preventDefault();

        const datos = new FormData(formulario);

        try {
            const resultado = await llamarServidor('guardar', {
                inscripcion_id: datos.get('inscripcion_id'),
                estado_asistencia: datos.get('estado_asistencia')
            });
            await Swal.fire('Correcto', resultado.mensaje, 'success');
            await cargarInscritos();
        } catch (error) {
            await Swal.fire('Error', error.message, 'error');
        }
    });

    filtroEvento.addEventListener('change', () => {
        cargarInscritos().catch(error => Swal.fire('Error', error.message, 'error'));
    });

    botonRecargar.addEventListener('click', () => {
        cargarInscritos().catch(error => Swal.fire('Error', error.message, 'error'));
    });

    Promise.all([cargarEventos(), cargarInscritos()])
        .catch(error => Swal.fire('Error', error.message, 'error'));
});
</script>
</body>
</html>