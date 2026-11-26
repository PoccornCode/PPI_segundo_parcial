function validarCampo(campo) {
    const vacio = campo.value.trim() === "";
    campo.classList.toggle("is-invalid", vacio);
    campo.classList.toggle("is-valid", !vacio);

    return !vacio;
}

const formulario = document.getElementById("formulario"),
    btnGuardar = document.getElementById("btnGuardar"),
    codigo = document.getElementById("codigo"),
    nombre = document.getElementById("nombre"),
    descripcion = document.getElementById("descripcion"),
    lugar = document.getElementById("lugar"),
    fecha = document.getElementById("fecha"),
    hora = document.getElementById("hora"),
    cupo_total = document.getElementById("cupo_total"),
    rutaController = "../forms/"

const campos = [codigo, nombre, descripcion, lugar, fecha, hora, cupo_total].filter(Boolean);
const camposMayusculas = [codigo, nombre, descripcion, lugar].filter(Boolean);

function validarFormularioEvento() {
    let todoValido = true;

    campos.forEach(function (campo) {
        const valido = validarCampo(campo);
        if (!valido) {
            todoValido = false;
        }
    });

    if (cupo_total) {
        const cupoNum = Number(cupo_total.value);
        const cupoValido = Number.isInteger(cupoNum) && cupoNum >= 1;
        cupo_total.classList.toggle("is-invalid", !cupoValido);
        cupo_total.classList.toggle("is-valid", cupoValido);
        if (!cupoValido) {
            todoValido = false;
        }
    }

    return todoValido;
}

if (formulario && btnGuardar) {
    // Validación al salir del campo
    campos.forEach(function (campo) {
        campo.addEventListener("blur", function () {
            validarCampo(campo);
        });
    });

    // Pasar textos a mayúsculas y validar en tiempo real
    camposMayusculas.forEach(function (campo) {
        campo.addEventListener("input", function () {
            campo.value = campo.value.toUpperCase();
            validarCampo(campo);
        });
    });

    if (fecha) {
        fecha.addEventListener("change", function () {
            validarCampo(fecha);
        });
    }

    if (hora) {
        hora.addEventListener("change", function () {
            validarCampo(hora);
        });
    }

    if (cupo_total) {
        cupo_total.addEventListener("input", function () {
            const cupoNum = Number(cupo_total.value);
            const valido = Number.isInteger(cupoNum) && cupoNum >= 1;
            cupo_total.classList.toggle("is-invalid", !valido);
            cupo_total.classList.toggle("is-valid", valido);
        });
    }

    btnGuardar.addEventListener("click", async function () {
        const formularioValido = validarFormularioEvento();
        if (!formularioValido) return;

        const modo = btnGuardar.textContent.trim();

        if (modo === "Guardar") {
            try {
                const datos = new FormData(formulario);
                datos.append("opcion", "guardar");

                btnGuardar.disabled = true;
                btnGuardar.textContent = "Procesando...";

                const respuesta = await fetch(rutaController+"DAOEvento.php", {
                    method: "POST",
                    body: datos
                });

                if (!respuesta.ok) {
                    throw new Error("Error HTTP: " + respuesta.status);
                }

                const resultado = await respuesta.json();
                if (resultado.estado) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Eventos Académicos",
                        text: resultado.mensaje,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    limpiarCampos();
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Eventos Académicos",
                        text: resultado.mensaje,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Eventos Académicos",
                    text: "Error de comunicación con el servidor",
                    showConfirmButton: false,
                    timer: 1500
                });
            } finally {
                btnGuardar.disabled = false;
                btnGuardar.textContent = "Guardar";
            }
        }

        if (modo === "Modificar") {
            try {
                const datos = new FormData(formulario);
                datos.append("opcion", "Modificar");
                datos.append("id", btnGuardar.dataset.id);

                btnGuardar.disabled = true;
                btnGuardar.textContent = "Procesando...";

                const respuesta = await fetch(rutaController+"DAOEvento.php", {
                    method: "POST",
                    body: datos
                });

                if (!respuesta.ok) {
                    throw new Error("Error HTTP: " + respuesta.status);
                }

                const resultado = await respuesta.json();
                if (resultado.estado) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Eventos Académicos",
                        text: resultado.mensaje,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "evento.php";
                    });
                } else {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Eventos Académicos",
                        text: resultado.mensaje,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Eventos Académicos",
                    text: "Error de comunicación con el servidor",
                    showConfirmButton: false,
                    timer: 1500
                });
            } finally {
                btnGuardar.disabled = false;
                btnGuardar.textContent = "Guardar";
            }
        }
    });
}

function limpiarCampos() {
    campos.forEach(function (campo) {
        campo.value = "";
        campo.classList.remove("is-invalid");
        campo.classList.remove("is-valid");
    });
}

document.addEventListener("DOMContentLoaded", async () => {
    const parametros = new URLSearchParams(window.location.search);
    const id = parametros.get("id");

    if (id) {
        try {
            const datos = new FormData();
            datos.append("opcion", "consultarIndividual");
            datos.append("id", id);

            Swal.fire({
                title: "Consultando...",
                text: "Espere",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            const respuesta = await fetch(rutaController+"DAOEvento.php", {
                method: "POST",
                body: datos
            });

            if (!respuesta.ok) {
                console.error(respuesta.status);
                throw new Error("Error HTTP: " + respuesta.status);
            }

            const resultado = await respuesta.json();
            Swal.close();

            if (resultado.estado) {
                if (codigo) codigo.value = resultado.datos.codigo ?? "";
                if (nombre) nombre.value = resultado.datos.nombre ?? "";
                if (descripcion) descripcion.value = resultado.datos.descripcion ?? "";
                if (lugar) lugar.value = resultado.datos.lugar ?? "";
                if (fecha) fecha.value = resultado.datos.fecha ?? "";
                if (hora) hora.value = resultado.datos.hora ?? "";
                if (cupo_total) cupo_total.value = resultado.datos.cupo_total ?? "";

                btnGuardar.textContent = "Modificar";
                btnGuardar.dataset.id = resultado.datos.id;
            }
        } catch (error) {
            console.error(error);
            Swal.close();
        }
    }
});