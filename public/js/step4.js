document.addEventListener("DOMContentLoaded", function () {
    document
        .getElementById("n_beneficiarios")
        .addEventListener("change", function () {
            var beneficiarios = parseInt(this.value);

            // Ocultar todos los beneficiarios
            document.getElementById("beneficiario1").style.display = "none";
            document.getElementById("beneficiario2").style.display = "none";
            document.getElementById("beneficiario3").style.display = "none";

            // Remover el "required" de los campos de los beneficiarios
            removeRequiredFields();

            // Mostrar el número adecuado de beneficiarios según la selección
            if (beneficiarios >= 1) {
                document.getElementById("beneficiario1").style.display =
                    "block";
                setRequiredFields(1);
            }
            if (beneficiarios >= 2) {
                document.getElementById("beneficiario2").style.display =
                    "block";
                setRequiredFields(2);
            }
            if (beneficiarios === 3) {
                document.getElementById("beneficiario3").style.display =
                    "block";
                setRequiredFields(3);
            }
        });

    const form = document.getElementById("formStep3");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Detiene el envío del formulario inicialmente

        // Verifica si hay al menos un beneficiario seleccionado
        const beneficiarios = parseInt(
            document.getElementById("n_beneficiarios").value
        );
        if (beneficiarios === 0) {
            Swal.fire({
                title: "Error",
                text: "Debes seleccionar al menos un beneficiario.",
                icon: "error",
                confirmButtonColor: "#28a745",
                confirmButtonText: "Entendido",
            });
            return; // Detiene el envío del formulario
        }

        // Verifica si los campos de los beneficiarios están llenos
        let valid = true;
        for (let i = 1; i <= beneficiarios; i++) {
            const nombres = document.getElementById(`nombres_s${i}`).value;
            const apellidos = document.getElementById(`apellidos_s${i}`).value;
            const genero = document.getElementById(`genero_s${i}`).value;

            if (!nombres || !apellidos || !genero) {
                valid = false;
                break;
            }
        }

        if (!valid) {
            Swal.fire({
                title: "Error",
                text: "Por favor, completa todos los campos requeridos de los beneficiarios.",
                icon: "error",
                confirmButtonColor: "#28a745",
                confirmButtonText: "Entendido",
            });
            return; // Detiene el envío del formulario
        }

        // Obtén los valores de los porcentajes de los campos
        var porcentaje1 =
            parseFloat(document.getElementById("porcentaje_s1").value) || 0;
        var porcentaje2 =
            parseFloat(document.getElementById("porcentaje_s2").value) || 0;
        var porcentaje3 =
            parseFloat(document.getElementById("porcentaje_s3").value) || 0;

        // Calcula la suma de los porcentajes
        var totalPorcentaje = porcentaje1 + porcentaje2 + porcentaje3;

        // Verifica si la suma es válida
        if (totalPorcentaje !== 100) {
            Swal.fire({
                title: "Error",
                text: "La suma de los porcentajes debe ser exactamente 100%.",
                icon: "error",
                confirmButtonColor: "#28a745",
                confirmButtonText: "Entendido",
            });
            return; // Detiene el envío del formulario
        }

        // Muestra la confirmación de envío del formulario
        Swal.fire({
            title: "¿Estás seguro de continuar?",
            text: "Recuerda que no podrás editar la información una vez continúes con el proceso de afiliación.",
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
});

function removeRequiredFields() {
    var fields = document.querySelectorAll(
        "#beneficiario1 input, #beneficiario2 input, #beneficiario3 input, #beneficiario1 select, #beneficiario2 select, #beneficiario3 select"
    );
    fields.forEach(function (field) {
        field.removeAttribute("required");
    });
}

function setRequiredFields(number) {
    for (var i = 1; i <= number; i++) {
        var fields = document.querySelectorAll(
            "#beneficiario" + i + " input, #beneficiario" + i + " select"
        );
        fields.forEach(function (field) {
            field.setAttribute("required", "required");
        });
    }
}

window.onload = function () {
    var inputFechaNacimiento = document.getElementById(
        "fecha_nacimiento_asegurado"
    );

    // Obtener la fecha actual
    var today = new Date();

    // Calcular el año que corresponde a 18 años atrás
    var year18YearsAgo = today.getFullYear() - 18;

    // Formatear la fecha mínima (hace 18 años)
    var month = (today.getMonth() + 1).toString().padStart(2, "0"); // Sumar 1 porque los meses son de 0 a 11
    var day = today.getDate().toString().padStart(2, "0");

    // Fecha mínima permitida (mayor de 18 años)
    var minDate = year18YearsAgo + "-" + month + "-" + day;

    // Establecer la fecha mínima en el campo de fecha
    inputFechaNacimiento.setAttribute("max", minDate);
};
