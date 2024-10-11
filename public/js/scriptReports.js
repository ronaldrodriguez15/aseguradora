// Obtener el token CSRF desde el meta tag en la vista
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// Función para obtener los registros seleccionados
function getSelectedRecords() {
    const selectedRecords = [];
    const checkboxes = document.querySelectorAll(".record-checkbox:checked");
    checkboxes.forEach((checkbox) => {
        selectedRecords.push(checkbox.value); // Añadir el valor del checkbox (ID del registro) al array
    });
    return selectedRecords;
}

// Manejar el click en el botón "Descargar PDFs"
document
    .getElementById("descargar-pdfs")
    .addEventListener("click", function (e) {
        e.preventDefault(); // Evitar el comportamiento predeterminado del enlace

        const selectedRecords = getSelectedRecords(); // Obtener los registros seleccionados

        if (selectedRecords.length === 0) {
            alert("No se ha seleccionado ningún registro.");
            return;
        }

        // Crear un formulario oculto para enviar los datos al servidor
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "/descargar-pdfs"; // Ruta donde se manejará la descarga en el servidor
        form.style.display = "none";

        // Agregar el token CSRF si es necesario (en Laravel)
        const tokenInput = document.createElement("input");
        tokenInput.type = "hidden";
        tokenInput.name = "_token";
        tokenInput.value = csrfToken; // Aquí se obtiene el token CSRF dinámicamente
        form.appendChild(tokenInput);

        // Agregar los IDs seleccionados como un input oculto
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "selected_records";
        input.value = JSON.stringify(selectedRecords); // Convertir el array en JSON
        form.appendChild(input);

        document.body.appendChild(form);
        form.submit(); // Enviar el formulario
    });
