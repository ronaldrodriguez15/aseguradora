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

// Función para imprimir el contenido del modal
function printModalContent(modalId) {
    var content = document.getElementById(modalId).innerHTML;
    var printWindow = window.open("", "_blank");
    printWindow.document.write(
        "<html><head><title>Impresión</title></head><body>"
    );
    printWindow.document.write(content);
    printWindow.document.write("</body></html>");
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
}

function confirmDelete(icon) {
    const userId = icon.parentElement.getAttribute("data-id");
    Swal.fire({
        title: "¿Estás seguro de inactivar el registro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, envía el formulario de eliminación
            document.getElementById(`formDelete-${userId}`).submit();
        }
    });
}

$("#btnNuevaCiudad").click(function (e) {
    e.preventDefault();
    $("#modalNuevaCiudad").modal("show");
});

// Depurar el botón de cerrar
$(".close").click(function () {
    $("#modalNuevaCiudad").modal("hide");
});

// Función para imprimir el contenido del modal
function printModalContent(modalId) {
    var content = document.getElementById(modalId).innerHTML;
    var printWindow = window.open("", "_blank");
    printWindow.document.write(
        "<html><head><title>Impresión</title></head><body>"
    );
    printWindow.document.write(content);
    printWindow.document.write("</body></html>");
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
}

function confirmDelete(icon) {
    const userId = icon.parentElement.getAttribute("data-id");
    Swal.fire({
        title: "¿Estás seguro de inactivar el registro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, envía el formulario de eliminación
            document.getElementById(`formDelete-${userId}`).submit();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formNuevaAseguradora");
    const polizaInput = document.getElementById("no_poliza");
    const nameInput = document.getElementById("name");
    const documentInput = document.getElementById("document_path");

    const polizaError = document.getElementById("poliza-error");
    const nameError = document.getElementById("name-error");
    const documentError = document.getElementById("document_path-error");
    const documentLabel = document.querySelector('label[for="document_path"]');

    form.addEventListener("submit", function (event) {
        let isValid = true;

        // Limpiar errores anteriores
        polizaError.style.display = "none";
        nameError.style.display = "none";
        documentError.style.display = "none";

        // Validar campo de nombre
        if (!polizaInput.value.trim()) {
            polizaError.style.display = "block";
            isValid = false;
        }

        // Validar campo de nombre
        if (!nameInput.value.trim()) {
            nameError.style.display = "block";
            isValid = false;
        }

        // Validar archivo
        if (!documentInput.files.length) {
            documentError.style.display = "block";
            isValid = false;
        }

        // Si el formulario no es válido, prevenir el envío
        if (!isValid) {
            event.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Valida que los campos estén correctos!",
            });
        }
    });

    documentInput.addEventListener("change", function () {
        // Verifica si hay un archivo seleccionado
        if (documentInput.files.length > 0) {
            const fileName = documentInput.files[0].name;
            // Actualiza el texto del label con el nombre del archivo
            documentLabel.textContent = fileName;
        } else {
            // Si no hay archivo seleccionado, muestra el texto por defecto
            documentLabel.textContent = "Seleccionar archivo";
        }
    });
});
