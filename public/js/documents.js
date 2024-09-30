document.addEventListener("DOMContentLoaded", function () {
    // Validación para el archivo EstaSSeguro
    document
        .getElementById("submitBtnEstaSSeguro")
        .addEventListener("click", function (e) {
            var documentInput = document.getElementById(
                "document_path_estasseguro"
            );
            var errorMessage = document.getElementById(
                "document_path_estasseguro-error"
            );

            if (documentInput.files.length === 0) {
                e.preventDefault(); // Evita que el formulario se envíe
                errorMessage.style.display = "block"; // Muestra el mensaje de error
                documentInput.classList.add("is-invalid"); // Marca el campo en rojo
            } else {
                errorMessage.style.display = "none"; // Oculta el mensaje de error
                documentInput.classList.remove("is-invalid"); // Elimina la marca en rojo
            }
        });

    // Validación para el archivo Libranza
    document
        .getElementById("submitBtnLibranza")
        .addEventListener("click", function (e) {
            var documentInput = document.getElementById(
                "document_path_libranza"
            );
            var errorMessage = document.getElementById(
                "document_path_libranza-error"
            );

            if (documentInput.files.length === 0) {
                e.preventDefault(); // Evita que el formulario se envíe
                errorMessage.style.display = "block"; // Muestra el mensaje de error
                documentInput.classList.add("is-invalid"); // Marca el campo en rojo
            } else {
                errorMessage.style.display = "none"; // Oculta el mensaje de error
                documentInput.classList.remove("is-invalid"); // Elimina la marca en rojo
            }
        });

    // Validación para el archivo Debito
    document
        .getElementById("submitBtnDebito")
        .addEventListener("click", function (e) {
            var documentInput = document.getElementById("document_path_debito");
            var errorMessage = document.getElementById(
                "document_path_debito-error"
            );

            if (documentInput.files.length === 0) {
                e.preventDefault(); // Evita que el formulario se envíe
                errorMessage.style.display = "block"; // Muestra el mensaje de error
                documentInput.classList.add("is-invalid"); // Marca el campo en rojo
            } else {
                errorMessage.style.display = "none"; // Oculta el mensaje de error
                documentInput.classList.remove("is-invalid"); // Elimina la marca en rojo
            }
        });

    // Mostrar nombre del archivo seleccionado para cada modal
    document
        .getElementById("document_path_estasseguro")
        .addEventListener("change", function (e) {
            var fileName = e.target.files[0]
                ? e.target.files[0].name
                : "Seleccionar archivo";
            e.target.nextElementSibling.innerHTML = fileName;
        });

    document
        .getElementById("document_path_libranza")
        .addEventListener("change", function (e) {
            var fileName = e.target.files[0]
                ? e.target.files[0].name
                : "Seleccionar archivo";
            e.target.nextElementSibling.innerHTML = fileName;
        });

    document
        .getElementById("document_path_debito")
        .addEventListener("change", function (e) {
            var fileName = e.target.files[0]
                ? e.target.files[0].name
                : "Seleccionar archivo";
            e.target.nextElementSibling.innerHTML = fileName;
        });
});
