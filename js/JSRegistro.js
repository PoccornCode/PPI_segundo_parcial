document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("formularioRegistro");
    const btnRegistrar = document.getElementById("btnRegistrar");

    formulario.addEventListener("submit", async (e) => {
        e.preventDefault();

        const campos = ["carnet", "nombre", "apellido", "usuario"];
        let valido = true;
        campos.forEach((id) => {
            const campo = document.getElementById(id);
            const vacio = campo.value.trim() === "";
            campo.classList.toggle("is-invalid", vacio);
            campo.classList.toggle("is-valid", !vacio);
            if (vacio) valido = false;
        });
        if (!valido) return;

        const datos = new FormData(formulario);
        datos.append("opcion", "guardar");
        btnRegistrar.disabled = true;
        btnRegistrar.textContent = "Procesando...";

        try {
            const respuesta = await fetch("forms/DAOAutoregistro.php", {
                method: "POST",
                body: datos
            });
            if (!respuesta.ok) throw new Error("Error HTTP: " + respuesta.status);
            const resultado = await respuesta.json();

            if (resultado.estado) {
                await Swal.fire({
                    icon: "success",
                    title: "Cuenta creada correctamente",
                    html: "Tu usuario es: <b>" + escaparHTML(datos.get("usuario")) +
                        "</b><br>Tu contraseña generada es:<br><code>" + escaparHTML(resultado.clave) +
                        "</code><br><br><b>Guarda estos datos antes de continuar.</b>",
                    confirmButtonText: "Ir al inicio de sesión"
                });
                window.location.href = "login.php";
            } else {
                await Swal.fire({ icon: "warning", title: "No se pudo registrar", text: resultado.mensaje || "Revisa los datos." });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({ icon: "error", title: "Error", text: "No se pudo completar el registro. Revisa la conexión y la base de datos." });
        } finally {
            btnRegistrar.disabled = false;
            btnRegistrar.textContent = "Registrarme";
        }
    });
});

function escaparHTML(valor) {
    return String(valor).replace(/[&<>"']/g, (caracter) => ({
        "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
    }[caracter]));
}
