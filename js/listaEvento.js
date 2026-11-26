
document.addEventListener("DOMContentLoaded", async () => {
    const contenedor = document.getElementById("eventos-table");
    const filtro = document.getElementById("table-filter");
    const paginacion = document.getElementById("table-pagination");
    const btnImprimir = document.getElementById("print-table");

    const contador = document.getElementById("total-registros");

    if (!contenedor) return;

    const tabla = new Tabulator(contenedor, {
        locale: "es-es",
        langs: {
            "es-es": {
                "pagination": {
                    "page_size": "Registros por página",
                    "first": "Primera",
                    "first_title": "Primera página",
                    "last": "Última",
                    "last_title": "Última página",
                    "prev": "Anterior",
                    "prev_title": "Página anterior",
                    "next": "Siguiente",
                    "next_title": "Página siguiente",
                    "all": "Todos",
                    "counter": {
                        "showing": "Mostrando",
                        "of": "de",
                        "rows": "registros",
                        "pages": "páginas"
                    }
                },
                "data": {
                    "loading": "Cargando...",
                    "error": "Error al cargar los datos"
                },
                "headerFilters": {
                    "default": "Filtrar columna..."
                }
            }
        },
        data: [],
        layout: "fitColumns",
        pagination: true,
        paginationElement: paginacion,
        paginationSize: 10,
        paginationSizeSelector: [10, 25, 50, 100],
        movableColumns: true,
        columns: [
            { title: "ID", field: "id", width: 70 },
            { title: "Código", field: "codigo", width: 130 },
            { title: "Nombre", field: "nombre" },
            { title: "Descripción", field: "descripcion" },
            { title: "Lugar", field: "lugar" },
            { title: "Fecha", field: "fecha", width: 120 },
            { title: "Hora", field: "hora", width: 100 },
            { title: "Cupo Total", field: "cupo_total", hozAlign: "center", width: 115 },
            { title: "Disponible", field: "cupo_disponible", hozAlign: "center", width: 120 },
            {
                title: "Acciones",
                field: "acciones",
                hozAlign: "center",
                headerSort: false,
                width: 120,
                formatter: function () {
                    return `
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-editar" title="Editar">
                        <i class="bi bi-pencil" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar" title="Eliminar">
                        <i class="bi bi-trash" aria-hidden="true"></i>
                    </button>
                    `;
                },
                cellClick: function (e, cell) {
                    const evento = cell.getRow().getData();

                    if (e.target.closest(".btn-editar")) {
                        location.href = "evento.php?id=" + evento.id;
                    }

                    if (e.target.closest(".btn-eliminar")) {
                        Swal.fire({
                            title: "Eventos Académicos",
                            text: "¿Confirmar eliminación del registro?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sí, confirmo",
                            cancelButtonText: "Cancelar"
                        }).then(async (result) => {
                            if (result.isConfirmed) {
                                try {
                                    const datos = new FormData();
                                    datos.append("id", evento.id);
                                    datos.append("opcion", "eliminar");

                                    const respuesta = await fetch("DAOEvento.php", {
                                        method: "POST",
                                        body: datos
                                    });

                                    if (!respuesta.ok) {
                                        throw new Error("Error de comunicación: " + respuesta.status);
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
                                            window.location.href = "lista-evento.php";
                                        });
                                    } else {
                                        Swal.fire({
                                            position: "top-end",
                                            icon: "error",
                                            title: "Eventos Académicos",
                                            text: resultado.mensaje || "No se pudo eliminar el registro",
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
                                }
                            }
                        });
                    }
                }
            }
        ]
    });

    function actualizarContador() {
        const total = tabla.getDataCount("active");
        if (contador) {
            contador.textContent = `Total de registros: ${total}`;
        }
    }

    tabla.on("dataLoaded", actualizarContador);
    tabla.on("dataFiltered", actualizarContador);
    tabla.on("dataChanged", actualizarContador);
    tabla.on("dataFiltered", function (filters, filas) {
        if (contador) {
            contador.textContent = `Total de registros: ${filas.length}`;
        }
    });

    try {
        Swal.fire({
            title: "Cargando...",
            text: "Espere",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });

        const datos = new FormData();
        datos.append("opcion", "consultar");

        const respuesta = await fetch("DAOEvento.php", {
            method: "POST",
            body: datos
        });

        if (!respuesta.ok) {
            throw new Error("Error HTTP: " + respuesta.status);
        }

        const eventos = await respuesta.json();
        Swal.close();

        if (!Array.isArray(eventos)) {
            throw new Error(eventos.error || "Respuesta no válida");
        }

        await tabla.replaceData(eventos);
        actualizarContador();
    } catch (error) {
        console.error("Error al cargar registros:", error);
        Swal.close();
        contenedor.textContent = "No se pudieron cargar los registros.";
    }

    if (filtro) {
        filtro.addEventListener("input", () => {
            const valor = filtro.value.trim();

            if (!valor) {
                tabla.clearFilter();
                return;
            }

            tabla.setFilter([
                [
                    { field: "codigo", type: "like", value: valor },
                    { field: "nombre", type: "like", value: valor },
                    { field: "descripcion", type: "like", value: valor },
                    { field: "lugar", type: "like", value: valor }
                ]
            ]);
        });
    }

    btnImprimir?.addEventListener("click", () => {
        window.print();
    });
});