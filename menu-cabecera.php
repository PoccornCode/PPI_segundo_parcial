<?php
// Cabecera común. Funciona tanto desde la raíz como desde /forms
$enForms = basename(dirname($_SERVER['SCRIPT_FILENAME'])) === 'forms';
$raiz = $enForms ? '../' : '';
$nombreSesion = $_SESSION['nombreEstudiante'] ?? 'Usuario';
?>
<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="<?php echo $raiz; ?>index.php" class="nav-link">Inicio</a>
      </li>
    </ul>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          <img src="<?php echo $raiz; ?>assets/img/user2-160x160.jpg" class="user-image rounded-circle shadow" alt="Usuario" />
          <span class="d-none d-xl-inline"><?php echo htmlspecialchars($nombreSesion); ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <li class="user-header text-bg-primary">
            <img src="<?php echo $raiz; ?>assets/img/user2-160x160.jpg" class="rounded-circle shadow" alt="Usuario" />
            <p><?php echo htmlspecialchars($nombreSesion); ?></p>
          </li>
          <li class="user-footer">
            <a href="<?php echo $raiz; ?>salir.php" class="btn btn-outline-danger float-end" id="cerrarSesion">Cerrar sesión</a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
<!--end::Header-->
