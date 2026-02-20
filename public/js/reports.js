$(document).ready(function () {
    $("#datatable").DataTable({
        columnDefs: [
            {
                defaultContent: "-",
                targets: "_all",
            },
        ],
        language: {
            search: "Buscar",
            emptyTable: "No hay datos disponibles en la tabla",
            infoEmpty: "Mostrando 0 a 0 de 0 entradas",
            lengthMenu: "Mostrar _MENU_ registros por página",
            info: "Mostrando página _PAGE_ de _PAGES_",
            zeroRecords: "No se encontraron registros coincidentes",
            infoFiltered: "(filtrado de _MAX_ registros en total)",
        },
    });

    $("#btnNuevaCiudad").click(function (e) {
        e.preventDefault();
        $("#modalNuevaCiudad").modal("show");
    });

    // Depurar el botón de cerrar
    $(".close").click(function () {
        $("#modalNuevaCiudad").modal("hide");
    });
});

// Formatear la cédula mientras se escribe
document.getElementById("cedula").addEventListener("input", function (e) {
    let cedula = e.target.value.replace(/\D/g, ""); // Elimina todo lo que no sean dígitos
    cedula = cedula.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Añade los puntos
    e.target.value = cedula; // Actualiza el valor en el input
});

// Obtener el token CSRF desde el meta tag en la vista
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

const buttonContainer = document.getElementById("button-container");
if (typeof pdfjsLib !== "undefined") {
    pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js";
}

// Función para obtener los registros seleccionados
function getSelectedRecords() {
    const selectedRecords = [];
    const checkboxes = document.querySelectorAll(".record-checkbox:checked");
    checkboxes.forEach((checkbox) => {
        selectedRecords.push(checkbox.value); // Añadir el valor del checkbox (ID del registro) al array
    });
    return selectedRecords;
}

// Manejar la selección de todos los checkboxes
document.getElementById("select-all").addEventListener("change", function () {
    const checkboxes = document.querySelectorAll(".record-checkbox");
    checkboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
        toggleButtons();
    });
});

// Verificar el estado de los checkboxes
document.querySelectorAll(".record-checkbox").forEach((checkbox) => {
    checkbox.addEventListener("change", toggleButtons);
});

// Función para mostrar u ocultar los botones
function toggleButtons() {
    if (!buttonContainer) {
        return;
    }
    const checkboxes = document.querySelectorAll(".record-checkbox");
    const isAnyChecked = Array.from(checkboxes).some(
        (checkbox) => checkbox.checked
    );

    // Mostrar u ocultar el contenedor de botones
    buttonContainer.style.display = isAnyChecked ? "block" : "none";
}

// Inicialmente ocultar los botones
toggleButtons();

$(document).on("click", ".verify-signature", function (event) {
    event.preventDefault();

    const inabilityId = this.getAttribute("data-id");
    if (!inabilityId) {
        return;
    }

    Swal.fire({
        title: "Consultando documentos...",
        html: "Por favor espera mientras verificamos el estado de las firmas.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    const formData = new FormData();
    formData.append("_token", csrfToken);
    formData.append("id", inabilityId);

    fetch("/consulta-viafirma", {
        method: "POST",
        body: formData,
    })
        .then(async (response) => {
            const rawText = await response.text();
            let payload = {};

            if (rawText && rawText.trim().length > 0) {
                try {
                    payload = JSON.parse(rawText);
                } catch (parseError) {
                    throw {
                        error: "Respuesta invalida del servidor.",
                        raw: rawText,
                    };
                }
            }

            if (!response.ok) {
                throw payload;
            }

            return payload;
        })
        .then((data) => {
            Swal.close();

            if (!data || !data.firstDocument) {
                const infoMessage = data && data.message
                    ? data.message
                    : "Los documentos aun no han sido firmados.";
                Swal.fire("Informacion", infoMessage, "info");
                return;
            }

            const canvas = document.getElementById("verifySignatureCanvas");
            if (!canvas || typeof pdfjsLib === "undefined") {
                Swal.fire("Error", "No se pudo preparar el visor de documentos.", "error");
                return;
            }

            const pdfUrl = `/storage/${data.firstDocument.document_path}`;
            const context = canvas.getContext("2d");
            context.clearRect(0, 0, canvas.width, canvas.height);

            pdfjsLib
                .getDocument(pdfUrl)
                .promise.then((pdf) => pdf.getPage(1))
                .then((page) => {
                    const scale = 1.5;
                    const viewport = page.getViewport({ scale });
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const renderContext = {
                        canvasContext: context,
                        viewport,
                    };

                    page.render(renderContext);
                    $("#verifySignatureModal").modal("show");
                })
                .catch(() => {
                    Swal.fire("Error", "No se pudo cargar el documento firmado.", "error");
                });
        })
        .catch((error) => {
            Swal.close();
            const message = error && error.error
                ? error.error
                : "Los documentos aun no han sido firmados o ocurrio un error. Por favor, intenta nuevamente.";
            Swal.fire("Error", message, "error");
        });
});

$(document).on("click", "#closeVerifyModal", function () {
    const canvas = document.getElementById("verifySignatureCanvas");
    if (canvas) {
        const context = canvas.getContext("2d");
        context.clearRect(0, 0, canvas.width, canvas.height);
    }
    $("#verifySignatureModal").modal("hide");
});

// Manejar el clic en el botón "Descargar Plano Focus"
const descargarPlanoBtn = document.getElementById("descargar-plano");
if (descargarPlanoBtn) {
    descargarPlanoBtn.addEventListener("click", function (e) {
        e.preventDefault();

        const selectedRecords = getSelectedRecords();

        if (selectedRecords.length === 0) {
            alert("No se ha seleccionado ningún registro.");
            return;
        }

        Swal.fire({
            title: "Descargando Excel",
            text: "Por favor, espera mientras se genera tu archivo...",
            icon: "info",
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => Swal.showLoading(),
        });

        const form = new FormData();
        form.append("_token", csrfToken);
        form.append("selected_records", JSON.stringify(selectedRecords));

        fetch("/descargar-plano-focus", {
            method: "POST",
            body: form,
        })
            .then((response) => {
                if (response.ok) {
                    return response.blob();
                }
                throw new Error("Network response was not ok.");
            })
            .then((blob) => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = `resultado_${Date.now()}.xlsx`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);

                Swal.close();
                Swal.fire({
                    title: "Éxito!",
                    text: "Excel descargado con éxito.",
                    icon: "success",
                    confirmButtonText: "OK",
                });
            })
            .catch((error) => {
                console.error("Error:", error);
                Swal.close();
                Swal.fire({
                    title: "Error!",
                    text: "Hubo un problema al procesar la solicitud.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
            });
    });
}

// Manejar el clic en el botón "Descargar Seguimiento"
const descargarSeguimientoBtn = document.getElementById("descargar-seguimiento");
if (descargarSeguimientoBtn) {
    descargarSeguimientoBtn.addEventListener("click", function (e) {
        e.preventDefault();

        const selectedRecords = getSelectedRecords();

        if (selectedRecords.length === 0) {
            alert("No se ha seleccionado ningún registro.");
            return;
        }

        Swal.fire({
            title: "Descargando Excel",
            text: "Por favor, espera mientras se genera tu archivo...",
            icon: "info",
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => Swal.showLoading(),
        });

        const form = new FormData();
        form.append("_token", csrfToken);
        form.append("selected_records", JSON.stringify(selectedRecords));

        fetch("/descargar-seguimiento-ventas", {
            method: "POST",
            body: form,
        })
            .then((response) => {
                if (response.ok) {
                    return response.blob();
                }
                throw new Error("Network response was not ok.");
            })
            .then((blob) => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = `resultado_${Date.now()}.xlsx`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);

                Swal.close();
                Swal.fire({
                    title: "Éxito!",
                    text: "Excel descargado con éxito.",
                    icon: "success",
                    confirmButtonText: "OK",
                });
            })
            .catch((error) => {
                console.error("Error:", error);
                Swal.close();
                Swal.fire({
                    title: "Error!",
                    text: "Hubo un problema al procesar la solicitud.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
            });
    });
}

const downloadPdfsBtn = document.getElementById("descargar-pdfs");
const downloadPdfsForm = document.getElementById("downloadPdfsForm");
const downloadPdfsInput = document.getElementById("downloadPdfsInput");

if (downloadPdfsBtn && downloadPdfsForm && downloadPdfsInput) {
    downloadPdfsBtn.addEventListener("click", function (e) {
        e.preventDefault();

        const selectedRecords = getSelectedRecords();

        if (!selectedRecords.length) {
            Swal.fire({
                title: "Sin selección",
                text: "Debes elegir al menos una afiliación para descargar sus documentos.",
                icon: "info",
                confirmButtonText: "Aceptar",
            });
            return;
        }

        downloadPdfsInput.value = JSON.stringify(selectedRecords);
        downloadPdfsForm.submit();
    });
}
