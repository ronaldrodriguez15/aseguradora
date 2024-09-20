// CONFIRMACION ENVIO DE FORMULARIO
var form = document.getElementById("formStep2"); // Selecciona tu formulario

form.addEventListener("submit", function (event) {
    event.preventDefault(); // Detiene el envío del formulario inicialmente

    Swal.fire({
        title: "¿Estás seguro de continuar?",
        text: "Recuerda que no podrás editar la información una vez continues con el proceso de afiliación",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit(); // Envía el formulario si el usuario confirma
        }
    });
});
