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

        // Añadir la clase correcta dependiendo de la edad
        if (edad < 18) {
            edadInput.classList.add("is-invalid");
        } else {
            edadInput.classList.add("is-valid");
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





