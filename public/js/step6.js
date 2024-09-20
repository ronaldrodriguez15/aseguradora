document.addEventListener("DOMContentLoaded", function () {
    const tienesMascotasSelect = document.getElementById("tienes_mascotas");
    const protegerMascotasContainer = document.getElementById(
        "proteger_mascotas_container"
    );
    const protegerMascotasSelect = document.getElementById("proteger_mascotas");
    const mascotasDiv = document.getElementById("mascotas");

    // Mostrar/ocultar contenedor de proteger mascotas
    tienesMascotasSelect.addEventListener("change", function () {
        if (tienesMascotasSelect.value === "si") {
            protegerMascotasContainer.style.display = "block";
        } else {
            protegerMascotasContainer.style.display = "none";
            mascotasDiv.style.display = "none"; // Ocultar también si no se tiene mascotas
            // Deshabilitar campos
            document
                .querySelectorAll(
                    "#mascotas input[required], #mascotas select[required]"
                )
                .forEach((el) => {
                    el.removeAttribute("required");
                });
        }
    });

    // Mostrar/ocultar campos de mascotas basados en proteger mascotas
    protegerMascotasSelect.addEventListener("change", function () {
        if (protegerMascotasSelect.value === "si") {
            mascotasDiv.style.display = "block";

            // Habilitar solo los campos de Mascota 1 como obligatorios
            document
                .querySelectorAll(
                    "#mascotas .form-group input, #mascotas .form-group select"
                )
                .forEach((el, index) => {
                    if (index < 7) {
                        // Solo los primeros 7 campos corresponden a Mascota 1
                        el.setAttribute("required", "required");
                    } else {
                        el.removeAttribute("required"); // Quitar required de Mascota 2 y 3
                    }
                });
        } else {
            mascotasDiv.style.display = "none";
            // Deshabilitar campos de mascotas
            document
                .querySelectorAll(
                    "#mascotas input[required], #mascotas select[required]"
                )
                .forEach((el) => {
                    el.removeAttribute("required");
                });
        }
    });
});

// CONFIRMACION ENVIO DE FORMULARIO
var form4 = document.getElementById("formStep5"); // Selecciona tu formulario

form4.addEventListener("submit", function (event) {
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
            form4.submit(); // Envía el formulario si el usuario confirma
        }
    });
});

$(document).ready(function () {
    // Maneja la referencia 1
    $("#seleccion_opcion_r1").change(function () {
        if ($(this).val() === "si") {
            $("#entidad-group-r1").hide(); // Oculta el campo "Entidad"
            $("#entidad_r1").prop("required", false); // Remueve "required"
            $("#cual-group-r1").show(); // Muestra el campo "¿Cuál?"
            $("#cual_r1").prop("required", true); // Agrega "required"
        } else {
            $("#entidad-group-r1").show(); // Muestra el campo "Entidad"
            $("#entidad_r1").prop("required", true); // Agrega "required"
            $("#cual-group-r1").hide(); // Oculta el campo "¿Cuál?"
            $("#cual_r1").prop("required", false); // Remueve "required"
        }
    });

    // Maneja la referencia 2
    $("#seleccion_opcion_r2").change(function () {
        if ($(this).val() === "si") {
            $("#entidad-group-r2").hide();
            $("#entidad_r2").prop("required", false);
            $("#cual-group-r2").show();
            $("#cual_r2").prop("required", true);
        } else {
            $("#entidad-group-r2").show();
            $("#entidad_r2").prop("required", true);
            $("#cual-group-r2").hide();
            $("#cual_r2").prop("required", false);
        }
    });

    // Maneja la referencia 3
    $("#seleccion_opcion_r3").change(function () {
        if ($(this).val() === "si") {
            $("#entidad-group-r3").hide();
            $("#entidad_r3").prop("required", false);
            $("#cual-group-r3").show();
            $("#cual_r3").prop("required", true);
        } else {
            $("#entidad-group-r3").show();
            $("#entidad_r3").prop("required", true);
            $("#cual-group-r3").hide();
            $("#cual_r3").prop("required", false);
        }
    });
});
