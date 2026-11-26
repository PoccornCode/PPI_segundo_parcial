<!DOCTYPE html>
<html lang="es">
<!-- ha25004 -->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Registro de Eventos</title>

  <!--begin::Theme Init (prevents flash of incorrect theme on load, #6043)-->
  <script src="../js/color-thema.js"></script>
  <!--end::Theme Init-->

  <!--begin::Accessibility Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <!--end::Accessibility Meta Tags-->

  <!--begin::Primary Meta Tags-->
  <meta name="title" content="Registro de Eventos - Eventos Académicos" />
  <meta name="description" content="Registro de eventos académicos y control de cupo disponible" />
  <meta name="keywords" content="bootstrap 5, adminlte, eventos académicos, inscripciones, asistencia" />
  <!--end::Primary Meta Tags-->

  <!--begin::Accessibility Features-->
  <meta name="supported-color-schemes" content="light dark" />
  <link rel="preload" href="../css/adminlte.css" as="style" />
  <!--end::Accessibility Features-->

  <!--begin::Fonts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.3.0/index.css" integrity="sha256-5UwXtC6TaPHCAbVQ59waEAqOssHyhn/twyuLRRWszzo=" crossorigin="anonymous" media="print" onload="this.media = 'all'" />
  <!--end::Fonts-->

  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->

  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->

  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="../css/adminlte.css" />
  <!--end::Required Plugin(AdminLTE)-->
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <!--begin::Header & Sidebar-->
    <?php
    include "../nav.php";
    include "../menu-lateral.php";
    ?>
    <!--end::Header & Sidebar-->

    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-2"></div>
            <div class="col-sm-6">
              <h1 class="mb-0 fs-3">Registro de Eventos</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <div class="row g-4">
            <div class="col-lg-2"></div>
            <!-- Formulario principal de registro de evento -->
            <div class="col-lg-8">
              <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                  <div class="card-title">Datos del Evento Académico</div>
                </div>
                <form id="formulario" method="POST" onsubmit="return false;">
                  <div class="card-body">
                    <div class="row g-3">
                      <div class="col-md-4">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" maxlength="30" placeholder="Ej. EVT-2026-01" required />
                        <div class="form-text">Código único del evento.</div>
                      </div>
                      <div class="col-md-8">
                        <label for="nombre" class="form-label">Nombre del evento</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" maxlength="120" placeholder="Ej. Congreso de Ingeniería de Sistemas" required />
                      </div>

                      <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Detalles, temática o requisitos del evento..."></textarea>
                      </div>

                      <div class="col-md-6">
                        <label for="lugar" class="form-label">Lugar</label>
                        <input type="text" class="form-control" id="lugar" name="lugar" maxlength="120" placeholder="Ej. Auditorio Facultad Multidisciplinaria" required />
                      </div>

                      <div class="col-md-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required />
                      </div>

                      <div class="col-md-3">
                        <label for="hora" class="form-label">Hora</label>
                        <input type="time" class="form-control" id="hora" name="hora" required />
                      </div>

                      <div class="col-md-6">
                        <label for="cupo_total" class="form-label">Cupo disponible</label>
                        <div class="input-group">
                          <span class="input-group-text"><i class="bi bi-people-fill"></i></span>
                          <input type="number" class="form-control" id="cupo_total" name="cupo_total" min="1" step="1" placeholder="Ej. 40" required />
                        </div>
                        <div class="form-text">Al guardar, el trigger inicializa el cupo disponible.</div>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardar">
                      <i class="bi bi-check-lg me-1"></i>Guardar
                    </button>
                  </div>
                </form>
              </div>
            </div>

          </div>
        </div>
      </div>
    </main>

    <!--begin::Footer-->
    <?php
    include "../footer.php"
    ?>
    <!--end::Footer-->
  </div>

  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)-->
  <!--begin::Required Plugin(Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <script src="../js/adminlte.js"></script>
  <!--end::Required Plugin(AdminLTE)-->
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!--begin::OverlayScrollbars Configure-->
  <script src="../js/para-el-menu.js"></script>
  <script src="../js/EventoJS.js"></script>
</body>

</html>