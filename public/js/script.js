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
    console.log(userId);
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
    console.log(userId);
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