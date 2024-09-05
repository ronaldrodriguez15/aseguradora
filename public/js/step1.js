document.getElementById('aseguradora').addEventListener('change', function() {
    // Obtener la opción seleccionada
    var selectedOption = this.options[this.selectedIndex];

    // Obtener el número de póliza de los datos de la opción seleccionada
    var noPoliza = selectedOption.getAttribute('data-poliza');

    // Asignar el número de póliza al campo correspondiente
    document.getElementById('no_poliza').value = noPoliza;
});