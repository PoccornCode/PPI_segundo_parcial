
function validarCampo(campo) {
    const vacio = campo.value.trim() === "";
    campo.classList.toggle("is-invalid", vacio);
    campo.classList.toggle("is-valid", !vacio);
    return !vacio;
}

document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("formulario");
    if (!formulario) return;

    const btnEntrar = document.getElementById("btnEntrar");
    const loginUsuario = document.getElementById("loginUsuario");
    const loginClave = document.getElementById("loginClave");

    const campos = [loginUsuario, loginClave];

    /* ---------- validación de campos vacíos ---------- */
    campos.forEach((campo) => {
        campo.addEventListener("blur", () => validarCampo(campo));
    });

    /* ---------- Enter también inicia sesión ---------- */
    formulario.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            btnEntrar.click();
        }
    });

    /* ---------- botón entrar ---------- */
    btnEntrar.addEventListener("click", async () => {
        const usuarioValido = validarCampo(loginUsuario);
        const claveValida = validarCampo(loginClave);
        if (!usuarioValido || !claveValida) return;

        const datos = new FormData(formulario);
        datos.append("opcion", "consultar");

        btnEntrar.disabled = true;
        btnEntrar.textContent = "Procesando...";
        let exito = false;

        try {
            const respuesta = await fetch("forms/DAOLogin.php", {
                method: "POST",
                body: datos
            });

            if (!respuesta.ok) {
                throw new Error("Error HTTP: " + respuesta.status);
            }

            const resultado = await respuesta.json();

            if (resultado.estado) {
                exito = true;
                await Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Eventos Académicos",
                    text: "Bienvenido " + resultado.datos.nombreEstudiante,
                    showConfirmButton: false,
                    timer: 1500
                });
                window.location.href = "panel.php";
            } else {
                Swal.fire({
                    icon: "info",
                    title: "Eventos Académicos",
                    text: resultado.mensaje
                });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo iniciar sesión."
            });
        } finally {
            btnEntrar.textContent = "Entrar";
            if (!exito) btnEntrar.disabled = false;
        }
    });
});
