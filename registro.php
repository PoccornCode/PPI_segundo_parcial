<?php
// El autorregistro es público: no requiere haber iniciado sesión.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si ya inició sesión, puede ir directamente al sistema.
if (!empty($_SESSION['autenticado'])) {
    header("Location: panel.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sistema de Eventos Académicos | Registro</title>
  <script src="js/color-thema.js"></script>
  <link rel="stylesheet" href="css/adminlte.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="login-page bg-body-secondary">
  <div class="login-box" style="width: 420px; max-width: 95%;">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <h1 class="mb-0"><b>Crear cuenta</b></h1>
        <p class="mb-0">Registro de estudiante</p>
      </div>
      <div class="card-body login-card-body">
        <form id="formularioRegistro" autocomplete="off">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="carnet" name="carnet" maxlength="25" placeholder="" required />
            <label for="carnet">Carnet</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="nombre" name="nombre" maxlength="80" placeholder="" required />
            <label for="nombre">Nombre</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="apellido" name="apellido" maxlength="80" placeholder="" required />
            <label for="apellido">Apellido</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="usuario" name="usuario" maxlength="50" placeholder="" required />
            <label for="usuario">Usuario</label>
          </div>
          <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            El sistema generará una contraseña automáticamente. Guárdala cuando aparezca.
          </div>
          <button type="submit" class="btn btn-primary w-100" id="btnRegistrar">Registrarme</button>
        </form>
        <p class="mt-3 mb-0 text-center">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/JSRegistro.js?v=<?php echo filemtime('js/JSRegistro.js'); ?>"></script>
</body>
</html>
