document.getElementById("aseguradora").addEventListener("change", function () {
    // Obtener la opción seleccionada
    var selectedOption = this.options[this.selectedIndex];

    // Obtener el número de póliza de los datos de la opción seleccionada
    var noPoliza = selectedOption.getAttribute("data-poliza");

    // Asignar el número de póliza al campo correspondiente
    document.getElementById("no_poliza").value = noPoliza;
});

document.getElementById("asesor_code").addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];

    const asesorName = selectedOption.getAttribute("data-name");

    document.getElementById("nombre_asesor").value = asesorName
        ? asesorName
        : "";
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
