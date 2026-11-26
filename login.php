
<?php
session_start();

// Si ya inició sesión, no tiene que ver el login otra vez
if (!empty($_SESSION['autenticado'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Sistema de Eventos Académicos | Login</title>

  <!--begin::Theme Init (prevents flash of incorrect theme on load, #6043)-->
  <script src="js/color-thema.js"></script>
  <!--end::Theme Init-->

  <!--begin::Accessibility Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <!--end::Accessibility Meta Tags-->

  <!--begin::Primary Meta Tags-->
  <meta name="title" content="Sistema de Eventos Académicos" />
  <meta name="description" content="Sistema de inscripción a eventos académicos universitarios" />
  <meta name="keywords" content="eventos académicos, estudiantes, inscripciones, universidad" />
  <!--end::Primary Meta Tags-->

  <!--begin::Accessibility Features-->
  <meta name="supported-color-schemes" content="light dark" />
  <link rel="preload" href="css/adminlte.css" as="style" />
  <!--end::Accessibility Features-->

  <!--begin::Fonts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.3.0/index.css" integrity="sha256-5UwXtC6TaPHCAbVQ59waEAqOssHyhn/twyuLRRWszzo=" crossorigin="anonymous" media="print" onload="this.media = 'all'" />
  <!--end::Fonts-->

  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->

  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="css/adminlte.css" />
  <!--end::Required Plugin(AdminLTE)-->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="login-page bg-body-secondary">
  <div class="login-box">

    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <h1 class="mb-0"><b>Eventos Académicos</b></h1>
      </div>

      <div class="card-body login-card-body">
        <p class="login-box-msg text-center small">Ingresa tus credenciales para iniciar sesión.</p>

        <form id="formulario" name="formulario" method="POST" action="#" autocomplete="off">

          <div class="input-group mb-3">
            <div class="form-floating">
              <input type="text" class="form-control" id="loginUsuario" name="loginUsuario" placeholder="" />
              <label for="loginUsuario">Usuario</label>
            </div>
            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
          </div>

          <div class="input-group mb-3">
            <div class="form-floating">
              <input type="password" class="form-control" id="loginClave" name="loginClave" placeholder="" />
              <label for="loginClave">Contraseña</label>
            </div>
            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
          </div>

          <div class="row">
            <div class="col-4">
              <button type="button" class="btn btn-primary" id="btnEntrar">
                Entrar
              </button>
            </div>
          </div>

        </form>

        <p class="mt-3 mb-0">
          ¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a>
        </p>

      </div>
    </div>

  </div>

  <!--begin::Required Plugin(Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <script src="js/adminlte.js"></script>
  <!--end::Required Plugin(AdminLTE)-->
  <script src="js/JSLogin.js?v=<?php echo filemtime('js/JSLogin.js'); ?>"></script>
</body>

</html>
