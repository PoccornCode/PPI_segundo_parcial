<?php
session_start();

if (empty($_SESSION['autenticado'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventos Académicos</title>
    <script src="js/color-thema.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/adminlte.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <?php
    include "menu-cabecera.php";
    include "menu-lateral.php";
    ?>
    <main class="app-main">
        <div class="app-content-header"></div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <h2>Bienvenido al sistema de eventos académicos</h2>

                        <p>
                            Hola, <?php echo htmlspecialchars($_SESSION['nombreEstudiante']); ?>
                        </p>

                        <p>Has iniciado sesión correctamente.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include "pie.php"; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="js/adminlte.js"></script>
<script src="js/para-el-menu.js"></script>
</body>
</html>
