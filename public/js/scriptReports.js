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

// Manejar el clic en el botón "Descargar PDFs"
document
    .getElementById("descargar-pdfs")
    .addEventListener("click", function (e) {
        e.preventDefault(); // Evitar el comportamiento predeterminado del enlace

        const selectedRecords = getSelectedRecords(); // Obtener los registros seleccionados

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
            willOpen: () => {
                Swal.showLoading();
            },
        });

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

        // Cerrar el spinner y mostrar el mensaje de éxito después de un pequeño tiempo
        setTimeout(() => {
            Swal.close(); // Cerrar el spinner

            // Mostrar mensaje de éxito
            Swal.fire({
                title: "Éxito!",
                text: "ZIP descargado con éxito.",
                icon: "success",
                confirmButtonText: "Aceptar", // Agregar un botón para cerrar la alerta de éxito
            });
        }, 1000); // Ajusta el tiempo según lo necesites
    });

// Manejar el clic en el botón "Descargar Plano Focus"
document
    .getElementById("descargar-plano")
    .addEventListener("click", function (e) {
        e.preventDefault(); // Evitar el comportamiento predeterminado del enlace

        const selectedRecords = getSelectedRecords(); // Obtener los registros seleccionados

        if (selectedRecords.length === 0) {
            alert("No se ha seleccionado ningún registro.");
            return;
        }

        // Mostrar el spinner mientras se procesa la solicitud
        Swal.fire({
            title: "Descargando Excel",
            text: "Por favor, espera mientras se genera tu archivo...",
            icon: "info",
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            },
        });

        // Crear un formulario oculto para enviar los datos al servidor
        const form = new FormData(); // Usar FormData para simplificar el proceso
        form.append("_token", csrfToken); // Agregar el token CSRF
        form.append("selected_records", JSON.stringify(selectedRecords)); // Agregar los IDs seleccionados

        // Usar fetch para enviar el formulario y manejar la respuesta
        fetch("/descargar-plano-focus", {
            method: "POST",
            body: form,
        })
            .then((response) => {
                if (response.ok) {
                    return response.blob(); // Retorna el blob para la descarga del archivo
                }
                throw new Error("Network response was not ok.");
            })
            .then((blob) => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = `resultado_${Date.now()}.xlsx`; // Nombre del archivo de descarga
                document.body.appendChild(a);
                a.click(); // Simula un clic en el enlace para iniciar la descarga
                a.remove(); // Elimina el enlace del DOM
                window.URL.revokeObjectURL(url); // Libera el objeto URL

                // Cerrar el spinner y mostrar mensaje de éxito
                Swal.close(); // Cerrar el spinner

                // Mostrar mensaje de éxito con SweetAlert
                Swal.fire({
                    title: "Éxito!",
                    text: "Excel descargado con éxito.",
                    icon: "success",
                    confirmButtonText: "OK",
                });
            })
            .catch((error) => {
                console.error("Error:", error);
                Swal.close(); // Cerrar el spinner en caso de error
                Swal.fire({
                    title: "Error!",
                    text: "Hubo un problema al procesar la solicitud.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
            });
    });
