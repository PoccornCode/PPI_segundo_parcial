<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Información técnica que no se ve en la página -->
    <meta charset="UTF-8">
    <title>Mi primera práctica web</title>
</head>

<body>

    <!-- Encabezado principal -->
    <header>
        <h1 id="inicio">Guía de Desarrollo Web</h1>
        <p>Esta es la estructura base para empezar a trabajar con HTML5.</p>
    </header>

    <!-- Menú de navegación con enlaces -->
    <nav>
        <h2>Navegación</h2>
        <ul>
            <!-- Enlaces internos: usan # y el id de la sección -->
            <li><a href="#introduccion">Ir a Introducción</a></li>
            <li><a href="#pasos">Ir a Pasos a seguir</a></li>
            <!-- Enlace externo: target="_blank" lo abre en una pestaña nueva -->
            <li><a href="https://developer.mozilla.org/es/" target="_blank">Documentación HTML</a></li>
        </ul>
    </nav>

    <!-- Contenido principal de la página -->
    <main>

        <!-- Primera sección con id para poder enlazarla desde el menú -->
        <section id="introduccion">
            <h2>Introducción</h2>
            <p>HTML es el esqueleto de la página: define los títulos, párrafos, listas e imágenes.</p>

            <!-- Lista desordenada (viñetas) -->
            <ul>
                <li><strong>HTML:</strong> estructura y contenido.</li>
                <li><strong>CSS:</strong> diseño, colores y apariencia.</li>
                <li><strong>JavaScript:</strong> interactividad.</li>
            </ul>

            <!-- Imagen: siempre lleva ruta (src) y texto alternativo (alt) -->
            <img src="ruta-de-tu-imagen.jpg" alt="Descripción breve de la imagen">
        </section>

        <!-- Segunda sección con lista ordenada -->
        <section id="pasos">
            <h2>Pasos para practicar</h2>
            <ol>
                <li>Instalar un editor de texto como VS Code o Notepad++.</li>
                <li>Guardar este archivo con extensión <strong>.html</strong> (por ejemplo, <code>index.html</code>).</li>
                <li>Escribir o modificar la estructura básica.</li>
                <li>Abrir el archivo en tu navegador para ver el resultado.</li>
            </ol>
        </section>

    </main>

    <!-- Pie de página -->
    <footer>
        <p>&copy; 2026 - Mi práctica de HTML</p>
        <!-- Enlace para volver arriba usando el id del h1 -->
        <a href="#inicio">Volver al inicio</a>
    </footer>

</body>
</html>