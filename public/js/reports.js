// Formatear la cédula mientras se escribe
document.getElementById('cedula').addEventListener('input', function (e) {
    let cedula = e.target.value.replace(/\D/g, ''); // Elimina todo lo que no sean dígitos
    cedula = cedula.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Añade los puntos
    e.target.value = cedula; // Actualiza el valor en el input
});

// Obtener el token CSRF desde el meta tag en la vista
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

// Función para obtener los registros seleccionados
function getSelectedRecords() {
    const selectedRecords = [];
    const checkboxes = document.querySelectorAll(".record-checkbox:checked");
    checkboxes.forEach((checkbox) => {
        selectedRecords.push(checkbox.value); // Añadir el valor del checkbox (ID del registro) al array
    });
    return selectedRecords;
}

// Manejar la selección de todos los checkboxes
document.getElementById('select-all').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('.record-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
        toggleButtons();
    });
});

// Verificar el estado de los checkboxes
document.querySelectorAll('.record-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', toggleButtons);
});

// Función para mostrar u ocultar los botones
function toggleButtons() {
    const checkboxes = document.querySelectorAll('.record-checkbox');
    const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    // Mostrar u ocultar el contenedor de botones
    document.getElementById('button-container').style.display = isAnyChecked ? 'block' : 'none';
}

// Inicialmente ocultar los botones
toggleButtons();

// Manejar el clic en el botón "Descargar PDFs"
document.getElementById("descargar-pdfs").addEventListener("click", function (e) {
    e.preventDefault();

    const selectedRecords = getSelectedRecords();

    if (selectedRecords.length === 0) {
        alert("No se ha seleccionado ningún registro.");
        return;
    }

    Swal.fire({
        title: "Descargando PDFs",
        text: "Por favor, espera mientras se genera tu ZIP...",
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => Swal.showLoading(),
    });

    const form = document.createElement("form");
    form.method = "POST";
    form.action = "/descargar-pdfs";
    form.style.display = "none";

    const tokenInput = document.createElement("input");
    tokenInput.type = "hidden";
    tokenInput.name = "_token";
    tokenInput.value = csrfToken;
    form.appendChild(tokenInput);

    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "selected_records";
    input.value = JSON.stringify(selectedRecords);
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();

    setTimeout(() => {
        Swal.close();
        Swal.fire({
            title: "Éxito!",
            text: "ZIP descargado con éxito.",
            icon: "success",
            confirmButtonText: "Aceptar",
        });
    }, 1000);
});

// Manejar el clic en el botón "Descargar Plano Focus"
document.getElementById("descargar-plano").addEventListener("click", function (e) {
    e.preventDefault();

    const selectedRecords = getSelectedRecords();

    if (selectedRecords.length === 0) {
        alert("No se ha seleccionado ningún registro.");
        return;
    }

    Swal.fire({
        title: "Descargando Excel",
        text: "Por favor, espera mientras se genera tu archivo...",
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => Swal.showLoading(),
    });

    const form = new FormData();
    form.append("_token", csrfToken);
    form.append("selected_records", JSON.stringify(selectedRecords));

    fetch("/descargar-plano-focus", {
        method: "POST",
        body: form,
    })
        .then((response) => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error("Network response was not ok.");
        })
        .then((blob) => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = `resultado_${Date.now()}.xlsx`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);

            Swal.close();
            Swal.fire({
                title: "Éxito!",
                text: "Excel descargado con éxito.",
                icon: "success",
                confirmButtonText: "OK",
            });
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.close();
            Swal.fire({
                title: "Error!",
                text: "Hubo un problema al procesar la solicitud.",
                icon: "error",
                confirmButtonText: "OK",
            });
        });
});

// Manejar el clic en el botón "Descargar Seguimiento"
document.getElementById("descargar-seguimiento").addEventListener("click", function (e) {
    e.preventDefault();

    const selectedRecords = getSelectedRecords();

    if (selectedRecords.length === 0) {
        alert("No se ha seleccionado ningún registro.");
        return;
    }

    Swal.fire({
        title: "Descargando Excel",
        text: "Por favor, espera mientras se genera tu archivo...",
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => Swal.showLoading(),
    });

    const form = new FormData();
    form.append("_token", csrfToken);
    form.append("selected_records", JSON.stringify(selectedRecords));

    fetch("/descargar-seguimiento_ventas", {
        method: "POST",
        body: form,
    })
        .then((response) => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error("Network response was not ok.");
        })
        .then((blob) => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = `resultado_${Date.now()}.xlsx`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);

            Swal.close();
            Swal.fire({
                title: "Éxito!",
                text: "Excel descargado con éxito.",
                icon: "success",
                confirmButtonText: "OK",
            });
        })
        .catch((error) => {
            console.error("Error:", error);
            Swal.close();
            Swal.fire({
                title: "Error!",
                text: "Hubo un problema al procesar la solicitud.",
                icon: "error",
                confirmButtonText: "OK",
            });
        });
});

