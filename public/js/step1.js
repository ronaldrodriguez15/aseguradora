document.getElementById("aseguradora").addEventListener("change", function () {
    // Obtener la opción seleccionada
    var selectedOption = this.options[this.selectedIndex];

    // Obtener el número de póliza de los datos de la opción seleccionada
    var noPoliza = selectedOption.getAttribute("data-poliza");

    // Asignar el número de póliza al campo correspondiente
    document.getElementById("no_poliza").value = noPoliza;
});

const birthDateInput = document.getElementById("fecha_nacimiento_asesor");
const ageInput = document.getElementById("edad");

birthDateInput.addEventListener("change", function () {
    const birthDate = new Date(birthDateInput.value);
    const age = calculateAge(birthDate);
    ageInput.value = age;
});

// Función para calcular la edad
function calculateAge(birthDate) {
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();

    const monthDifference = today.getMonth() - birthDate.getMonth();

    // Ajustar la edad si el cumpleaños no ha pasado aún en el año actual
    if (
        monthDifference < 0 ||
        (monthDifference === 0 && today.getDate() < birthDate.getDate())
    ) {
        age--;
    }
    return age;
}
