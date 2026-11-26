<?php
// Menú lateral común. Funciona tanto desde la raíz como desde /forms
$enForms = basename(dirname($_SERVER['SCRIPT_FILENAME'])) === 'forms';
$raiz  = $enForms ? '../' : '';
$forms = $enForms ? ''    : 'forms/';
$pagina = basename($_SERVER['SCRIPT_FILENAME']);
?>
<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="<?php echo $raiz; ?>index.php" class="brand-link">
      <img src="<?php echo $raiz; ?>assets/img/AdminLTELogo.png" alt="Logo" class="brand-image opacity-75 shadow" />
      <span class="brand-text fw-light">Eventos Académicos</span>
    </a>
  </div>
  <div class="sidebar-wrapper">
    <nav class="mt-2" aria-label="Main navigation">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false" id="navigation">
        <li class="nav-item">
          <a href="<?php echo $raiz; ?>index.php" class="nav-link <?php echo $pagina === 'index.php' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-house"></i>
            <p>Inicio</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo $forms; ?>estudiantes.php" class="nav-link <?php echo $pagina === 'estudiantes.php' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-people"></i>
            <p>Estudiantes</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
<!--end::Sidebar-->
