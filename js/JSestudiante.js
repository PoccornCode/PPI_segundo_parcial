function validarCampo(campo) {
    const vacio = campo.value.trim() === "";
    campo.classList.toggle("is-invalid", vacio);
    campo.classList.toggle("is-valid", !vacio);

    return !vacio;
}

const formulario = document.getElementById("formulario");
const btnGuardar = document.getElementById("btnGuardar");
const btnCancelar = document.getElementById("btnCancelar");
const carnet = document.getElementById("carnet");
const nombre = document.getElementById("nombre");
const apellido = document.getElementById("apellido");
const usuario = document.getElementById("usuario");

const campos = [carnet, nombre, apellido, usuario];

if (formulario) {
    ////////////////////VALIDACION DE CAMPOS VACIOS

    campos.forEach(function (campo) {
        campo.addEventListener("blur", function () {
            validarCampo(campo);
        });
    });

    ////////////////////PASAR NOMBRE Y APELLIDO A MAYUSCULAS

    const campos1 = [nombre, apellido];

    campos1.forEach(function (campo) {
        campo.addEventListener("input", function () {
            campo.value = campo.value.toUpperCase();
            validarCampo(campo);
        });
    });

    ////////////////////BOTON CANCELAR

    btnCancelar.addEventListener("click", function() {
        window.location.href = "../panel.php";
    });

    ////////////////////GUARDAR ESTUDIANTE

    btnGuardar.addEventListener("click", async function () {

        const carnetValido = validarCampo(carnet);
        const nombreValido = validarCampo(nombre);
        const apellidoValido = validarCampo(apellido);
        const usuarioValido = validarCampo(usuario);

        if (carnetValido && nombreValido && apellidoValido && usuarioValido) {

            try {
                const datos = new FormData(formulario);
                datos.append("opcion", "guardar");

                btnGuardar.disabled = true;
                btnGuardar.textContent = "Procesando...";

                // Enviar datos 
                // await hace que JavaScript ESPERE
                const respuesta = await fetch("DAOEstudiante.php", {
                    method: "POST",
                    body: datos
                });

                // Verificar respuesta HTTP
                if (!respuesta.ok) {
                    throw new Error("Error HTTP: " + respuesta.status);
                }

                // Esperar a que DAO termine
                const resultado = await respuesta.json();

                if (resultado.estado) {
                    //todo salio bien
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Eventos Académicos",
                        html: resultado.mensaje +
                            "<br><br><b>Usuario:</b> " + resultado.usuario +
                            "<br><b>Contraseña generada:</b><br><code>" + resultado.clave + "</code>",
                        showConfirmButton: true
                    });

                    limpiarCampos();

                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "warning",
                        title: "Eventos Académicos",
                        text: resultado.mensaje,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

            } catch (error) {
                console.error(error);

                Swal.fire({
                    icon: "error",
                    title: "Eventos Académicos",
                    text: "No se pudieron guardar los datos"
                });

            } finally {
                btnGuardar.disabled = false;
                btnGuardar.textContent = "Guardar";
            }

        }
    });
}

///////////////////LIMPIAR TODOS LOS CAMPOS///////////

function limpiarCampos() {
    campos.forEach(function (campo) {
        campo.value = "";
        campo.classList.remove("is-invalid");
        campo.classList.remove("is-valid");
    });
}

//////////PROCESA AL TERMINAR DE CARGAR LA PAGINA

document.addEventListener("DOMContentLoaded", async () => {

    const cerrarSesion = document.getElementById("cerrarSesion");

    cerrarSesion?.addEventListener("click", function (e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Cerrar sesión?',
            text: '¿Estás seguro que deseas salir del sistema?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, cerrar sesión',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../salir.php';
            }
        });
    });

});
