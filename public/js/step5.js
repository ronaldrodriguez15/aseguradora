document.addEventListener("DOMContentLoaded", function () {
    var selectOtro = document.getElementById("otro");
    var inputCual = document.getElementById("cual");
    var formGroupCual = inputCual.closest(".form-group");

    const fields = [
        "cancer",
        "corazon",
        "diabetes",
        "enf_hepaticas",
        "enf_neurologicas",
        "pulmones",
        "presion_arterial",
        "rinones",
        "infeccion_vih",
        "perdida_funcional_anatomica",
        "accidentes_labores_ocupacion",
        "hospitalizacion_intervencion_quirurgica",
        "enfermedad_diferente",
        "enf_cerebrovasculares",
        "cirugias",
        "alcoholismo",
        "tabaquismo",
        "enf_congenitas",
        "enf_colageno",
        "enf_hematologicas",
    ];

    const descripcionDeEnfermedades = document.getElementById(
        "descripcion_de_enfermedades"
    );
    const formFields = fields.map((id) => document.getElementById(id));

    function checkFields() {
        const anyFieldYes = formFields.some((field) => field.value === "si");

        if (anyFieldYes) {
            descripcionDeEnfermedades.style.display = "block";
            descripcionDeEnfermedades.required = true;
        } else {
            descripcionDeEnfermedades.style.display = "none";
            descripcionDeEnfermedades.required = false;
        }
    }

    // Check fields on page load
    checkFields();

    // Check fields whenever any of the select fields change
    formFields.forEach((field) => {
        field.addEventListener("change", checkFields);
    });

    selectOtro.addEventListener("change", function () {
        if (selectOtro.value === "si") {
            formGroupCual.style.display = "block";
            inputCual.required = true;
        } else {
            formGroupCual.style.display = "none";
            inputCual.required = false;
            inputCual.value = "";
        }
    });

    // Al cargar la página, ocultar el campo si no está seleccionado "sí"
    if (selectOtro.value !== "si") {
        formGroupCual.style.display = "none"; // Ocultar el campo inicialmente
    }
});

// CONFIRMACION ENVIO DE FORMULARIO
var form4 = document.getElementById("formStep4"); // Selecciona tu formulario

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
