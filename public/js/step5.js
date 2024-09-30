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

    const descripcionDeEnfermedades = document.getElementById("descripcion_de_enfermedades");
    const descripcionDeEnfermedadesInput = document.getElementById("descripcion_de_enfermedades_input");
    const formFields = fields.map((id) => document.getElementById(id));

    function checkFields() {
        const anyFieldYes = formFields.some((field) => field.value === "si");

        if (anyFieldYes) {
            descripcionDeEnfermedades.style.display = "block";
            descripcionDeEnfermedadesInput.required = true; // Asegúrate de que se requiere
        } else {
            descripcionDeEnfermedades.style.display = "none";
            descripcionDeEnfermedadesInput.required = false; // Eliminar requerido
            descripcionDeEnfermedadesInput.value = ""; // Limpiar el valor
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

    // Confirmación y validación en el envío del formulario
    var form4 = document.getElementById("formStep4"); // Selecciona tu formulario

    form4.addEventListener("submit", function (event) {
        event.preventDefault(); // Detener el envío predeterminado del formulario

        // Verificar si el textarea es requerido y está vacío
        if (
            descripcionDeEnfermedadesInput.required &&
            descripcionDeEnfermedadesInput.value.trim() === ""
        ) {
            descripcionDeEnfermedadesInput.setCustomValidity("Este campo es obligatorio.");
            descripcionDeEnfermedadesInput.reportValidity(); // Mostrar el mensaje de error
            return; // Detener el envío si está vacío
        }

        // Mostrar la ventana de confirmación
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
});
