<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Evento Académico</title>
  <link rel="stylesheet" href="registro_evento.css">
</head>
<body>
  <main class="contenedor-evento">
    <section class="tarjeta-evento">
      <header class="encabezado-evento">
        <h1>Registro de evento académico</h1>
        <p>Completa los datos que se almacenarán en la tabla de eventos.</p>
      </header>

      <div class="cuerpo-evento">
        <form action="guardar_evento.php" method="POST">
          <div class="rejilla">
            <div class="campo col-4">
              <label for="codigo">Código</label>
              <input type="text" id="codigo" name="codigo" maxlength="30" placeholder="Ej. EVT-2026-01" required>
              <span class="ayuda">Identificador único del evento.</span>
            </div>

            <div class="campo col-8">
              <label for="nombre">Nombre del evento</label>
              <input type="text" id="nombre" name="nombre" maxlength="120" placeholder="Ej. Seminario de Bases de Datos" required>
            </div>

            <div class="campo col-12">
              <label for="descripcion">Descripción</label>
              <textarea id="descripcion" name="descripcion" placeholder="Temas, ponentes o detalles generales del evento..."></textarea>
            </div>

            <div class="campo col-6">
              <label for="lugar">Lugar</label>
              <input type="text" id="lugar" name="lugar" maxlength="120" placeholder="Ej. Auditorio Fac. Multidisciplinaria" required>
            </div>

            <div class="campo col-3">
              <label for="fecha">Fecha</label>
              <input type="date" id="fecha" name="fecha" required>
            </div>

            <div class="campo col-3">
              <label for="hora">Hora</label>
              <input type="time" id="hora" name="hora" required>
            </div>

            <div class="campo col-4">
              <label for="cupo_total">Cupo disponible</label>
              <input type="number" id="cupo_total" name="cupo_total" min="1" step="1" placeholder="Ej. 50" required>
              <span class="ayuda">Al insertarse, el trigger inicializa el cupo disponible.</span>
            </div>
          </div>

          <div class="acciones">
            <button type="reset" class="btn-limpiar">Limpiar</button>
            <button type="submit" class="btn-guardar">Guardar evento</button>
          </div>
        </form>
      </div>
    </section>
  </main>
</body>
</html>