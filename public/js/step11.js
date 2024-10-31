document.getElementById("aseguradora").addEventListener("change", function () {
    // Obtener la opción seleccionada
    var selectedOption = this.options[this.selectedIndex];

    // Obtener el número de póliza de los datos de la opción seleccionada
    var noPoliza = selectedOption.getAttribute("data-poliza");

    const polizaInput = document.getElementById("no_poliza");

    // Asignar el número de póliza al campo correspondiente
    polizaInput.value = noPoliza;

    // Limpiar clases previas
    polizaInput.classList.remove("is-valid", "is-invalid");

    // Añadir la clase correcta dependiendo de la edad
    if (polizaInput.value === "") {
        polizaInput.classList.add("is-invalid");
    } else {
        polizaInput.classList.add("is-valid");
    }
});

document.getElementById("asesor_code").addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];

    const asesorName = selectedOption.getAttribute("data-name");
    const asesorInput = document.getElementById("nombre_asesor");

    asesorInput.value = asesorName ? asesorName : "";

    asesorInput.classList.remove("is-valid", "is-invalid");

    if (asesorName === null) {
        asesorInput.classList.add("is-invalid");
    } else {
        asesorInput.classList.add("is-valid");
    }
});

// Evento cuando el usuario selecciona una fecha
document
    .getElementById("fecha_nacimiento_asesor")
    .addEventListener("change", function () {
        const fechaNacimiento = this.value;
        const edad = calcularEdad(fechaNacimiento);
        const edadInput = document.getElementById("edad");

        // Actualizar el campo de edad
        edadInput.value = edad;

        // Limpiar clases previas
        edadInput.classList.remove("is-valid", "is-invalid");

        // Validación de la edad
        if (edad < 18 || edad > 58) { // Cambiar && por ||
            edadInput.classList.add("is-invalid");
            // Aquí puedes agregar un mensaje de error
            document.getElementById("edadError").textContent = 'La edad debe estar entre 18 y 58 años.';
            document.getElementById("edadError").style.display = 'block'; // Mostrar el mensaje de error
        } else {
            edadInput.classList.add("is-valid");
            document.getElementById("edadError").style.display = 'none'; // Ocultar el mensaje de error
        }

        // Validación de la fecha de nacimiento
        const maxDate = new Date("2005-12-31");
        const selectedDate = new Date(fechaNacimiento);

        if (selectedDate > maxDate) {
            this.classList.add("is-invalid");
            this.setCustomValidity(
                "La fecha no puede ser posterior al 31 de diciembre de 2005."
            );
        } else {
            this.classList.remove("is-invalid");
            this.setCustomValidity(""); // Restablecer el estado del mensaje de error
        }
    });

// Función para calcular la edad a partir de la fecha de nacimiento
function calcularEdad(fechaNacimiento) {
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();
    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }
    return edad;
}


document.addEventListener("DOMContentLoaded", function () {
    const valorIbcBasico = document.getElementById("valor_ibc_basico");
    const errorValor = document.getElementById("errorValor");
    const latestSalaryString = document.getElementById("latest_salary").value; // Obtener el salario más reciente como string

    // Limpiar el valor y convertirlo a número
    const latestSalary = parseInt(latestSalaryString.replace(/\./g, ''), 10); // Elimina puntos antes de convertir

    const min = latestSalary; // Salario mínimo
    const max = latestSalary * 25; // Salario máximo (25 veces el salario más reciente)

    // Función para formatear el número con puntos
    function formatearConPuntos(valor) {
        return valor.replace(/\D/g, "") // Elimina todo excepto dígitos
            .replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Agrega los puntos para miles
    }

    // Función para validar si el valor numérico está en el rango
    function validarMonto(valorNumerico) {
        if (valorNumerico < min || valorNumerico > max) {
            // Mostrar mensaje de error dinámico
            errorValor.innerHTML = `El valor debe estar entre $${formatearConPuntos(min.toString())} y $${formatearConPuntos(max.toString())}.`;
            errorValor.style.display = "block"; // Muestra el mensaje de error
            return false;
        } else {
            errorValor.style.display = "none"; // Oculta el mensaje de error
            return true;
        }
    }

    // Escuchar el evento 'input' para formatear el número y validar
    valorIbcBasico.addEventListener("input", function () {
        // Formatear el valor con puntos
        const valorFormateado = formatearConPuntos(this.value);
        this.value = valorFormateado;

        // Convertir el valor formateado a número sin puntos para validación
        const valorNumerico = parseInt(this.value.replace(/\./g, ''), 10);

        // Validar si el valor numérico está dentro del rango permitido
        if (!isNaN(valorNumerico)) {
            validarMonto(valorNumerico);
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const aseguradoraSelect = document.getElementById("aseguradora");
    const valPrevexequialInput = document.getElementById("val_prevexequial_eclusivo");

    aseguradoraSelect.addEventListener("change", function () {
        // Limpiar el input antes de actualizarlo
        valPrevexequialInput.value = "";

        // Obtener el valor del atributo data-val-prevexequial de la opción seleccionada
        const selectedOption = aseguradoraSelect.options[aseguradoraSelect.selectedIndex];
        const valPrevexequial = selectedOption.getAttribute("data-val-prevexequial");

        // Actualizar el valor del input, formateando si es necesario
        if (valPrevexequial) {
            valPrevexequialInput.value = formatearConPuntos(valPrevexequial);
            valPrevexequialInput.classList.add("is-valid");
        } else {
            valPrevexequialInput.classList.add("is-invalid");
        }
    });

    // Función para formatear el número con puntos
    function formatearConPuntos(valor) {
        return valor.replace(/\D/g, "") // Elimina todo excepto dígitos
            .replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Agrega los puntos para miles
    }
});








