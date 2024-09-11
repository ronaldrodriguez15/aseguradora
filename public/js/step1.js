document.getElementById("aseguradora").addEventListener("change", function () {
    // Obtener la opción seleccionada
    var selectedOption = this.options[this.selectedIndex];

    // Obtener el número de póliza de los datos de la opción seleccionada
    var noPoliza = selectedOption.getAttribute("data-poliza");

    // Asignar el número de póliza al campo correspondiente
    document.getElementById('no_poliza').value = noPoliza;
});

document.getElementById('asesor_code').addEventListener('change', function() {

    const selectedOption = this.options[this.selectedIndex];

    const asesorName = selectedOption.getAttribute('data-name');

    document.getElementById('nombre_asesor').value = asesorName ? asesorName : '';;
});
