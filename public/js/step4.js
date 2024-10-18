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

        // Verifica si hay beneficiarios seleccionados
        const beneficiarios = parseInt(document.getElementById("n_beneficiarios").value);

        // Si no hay beneficiarios (valor 0), se permite enviar el formulario
        if (beneficiarios === 0) {
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
            return; // Se detiene aquí si no hay beneficiarios
        }

        // Verifica si los campos de los beneficiarios están llenos (solo si beneficiarios > 0)
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
            return; // Detiene el envío del formulario si los campos no están completos
        }

        // Obtén los valores de los porcentajes de los beneficiarios
        let totalPorcentaje = 0;
        for (let i = 1; i <= beneficiarios; i++) {
            totalPorcentaje += parseFloat(document.getElementById(`porcentaje_s${i}`).value) || 0;
        }

        // Verifica si la suma de los porcentajes es válida
        if (totalPorcentaje !== 100) {
            Swal.fire({
                title: "Error",
                text: "La suma de los porcentajes debe ser exactamente 100%.",
                icon: "error",
                confirmButtonColor: "#28a745",
                confirmButtonText: "Entendido",
            });
            return; // Detiene el envío del formulario si los porcentajes no suman 100%
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

document.addEventListener("DOMContentLoaded", function () {
    const noIdentificacion = document.getElementById("no_identificacion");
    const confirmNoIdentificacion = document.getElementById("confirm_no_identificacion");

    // Función para formatear el número con puntos al escribir
    function formatearConPuntos(valor) {
        return valor.replace(/\D/g, "") // Elimina todo excepto dígitos
            .replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Agrega los puntos para miles
    }

    // Escuchar los eventos de entrada para formatear el campo
    noIdentificacion.addEventListener("input", function () {
        const valorFormateado = formatearConPuntos(this.value);
        this.value = valorFormateado;
        validarIdentificaciones();
    });

    confirmNoIdentificacion.addEventListener("input", function () {
        const valorFormateado = formatearConPuntos(this.value);
        this.value = valorFormateado;
        validarIdentificaciones();
    });

    // Función para validar si ambos campos coinciden
    function validarIdentificaciones() {
        const noIdentificacionValor = noIdentificacion.value.replace(/\./g, ''); // Eliminar puntos
        const confirmNoIdentificacionValor = confirmNoIdentificacion.value.replace(/\./g, ''); // Eliminar puntos

        if (noIdentificacionValor !== "" && confirmNoIdentificacionValor !== "") {
            if (noIdentificacionValor === confirmNoIdentificacionValor) {
                noIdentificacion.classList.add("is-valid");
                noIdentificacion.classList.remove("is-invalid");
                confirmNoIdentificacion.classList.add("is-valid");
                confirmNoIdentificacion.classList.remove("is-invalid");
            } else {
                noIdentificacion.classList.add("is-invalid");
                noIdentificacion.classList.remove("is-valid");
                confirmNoIdentificacion.classList.add("is-invalid");
                confirmNoIdentificacion.classList.remove("is-valid");
            }
        }
    }

    // Bloquear copiar/pegar en el campo de confirmación
    confirmNoIdentificacion.addEventListener('paste', function (e) {
        e.preventDefault(); // Evita que se pegue contenido
    });
    confirmNoIdentificacion.addEventListener('copy', function (e) {
        e.preventDefault(); // Evita que se copie contenido
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const noIdentificacions1 = document.getElementById("n_identificacion_s1");
    const noIdentificacions2 = document.getElementById("n_identificacion_s2");
    const noIdentificacions3 = document.getElementById("n_identificacion_s3");

    // Función para formatear el número con puntos al escribir
    function formatearConPuntos(valor) {
        return valor.replace(/\D/g, "") // Elimina todo excepto dígitos
            .replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Agrega los puntos para miles
    }

    // Escuchar los eventos de entrada para formatear el campo
    noIdentificacions1.addEventListener("input", function () {
        const valorFormateado = formatearConPuntos(this.value);
        this.value = valorFormateado;
    });

    noIdentificacions2.addEventListener("input", function () {
        const valorFormateado = formatearConPuntos(this.value);
        this.value = valorFormateado;
    });

    noIdentificacions3.addEventListener("input", function () {
        const valorFormateado = formatearConPuntos(this.value);
        this.value = valorFormateado;
    });
});





