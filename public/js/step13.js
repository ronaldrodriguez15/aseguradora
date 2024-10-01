// validación de correos electronicos
document.addEventListener("DOMContentLoaded", function () {
    const emailInput = document.getElementById("email_corporativo");
    const confirmEmailInput = document.getElementById("email_confirmacion");

    // Deshabilitar copiar y pegar en el campo de confirmación
    confirmEmailInput.addEventListener("paste", function (e) {
        e.preventDefault();
    });

    confirmEmailInput.addEventListener("copy", function (e) {
        e.preventDefault();
    });

    // Validación en caliente
    function validateEmails() {
        if (emailInput.value && confirmEmailInput.value) {
            if (emailInput.value === confirmEmailInput.value) {
                emailInput.classList.remove("is-invalid");
                confirmEmailInput.classList.remove("is-invalid");
            } else {
                emailInput.classList.add("is-invalid");
                confirmEmailInput.classList.add("is-invalid");
            }
        } else {
            // Cuando uno de los campos está vacío, no se aplica el estado 'is-invalid'
            emailInput.classList.remove("is-invalid");
            confirmEmailInput.classList.remove("is-invalid");
        }
    }

    emailInput.addEventListener("input", validateEmails);
    confirmEmailInput.addEventListener("input", validateEmails);
});

document
    .getElementById("valor_ibc_basico")
    .addEventListener("input", function (e) {
        let input = e.target.value;

        // Eliminar cualquier caracter que no sea número
        input = input.replace(/[\D\s\._\-]+/g, "");

        // Si el input está vacío, no hacer nada
        if (input === "") {
            e.target.value = "";
            return;
        }

        // Convertir el valor a entero para eliminar cualquier posible error
        let inputNumber = parseInt(input, 10);

        // Aplicar el formato de moneda con separadores de miles sin decimales
        e.target.value = new Intl.NumberFormat("es-CO", {
            style: "decimal",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(inputNumber);
    });

//campo identificador
document.getElementById("aseguradora").addEventListener("change", function () {
    // Get the selected option
    var selectedOption = this.options[this.selectedIndex];

    // Get the value of the 'data-identificador' attribute
    var identificador = selectedOption.getAttribute("data-identificador");

    // Set the value of the 'identificador' field
    var identificadorInput = document.getElementById("identificador");
    identificadorInput.value = identificador || "";

    // Apply or remove 'is-valid' class based on the field value
    if (identificadorInput.value) {
        identificadorInput.classList.add("is-valid");
        identificadorInput.classList.remove("is-invalid");
    } else {
        identificadorInput.classList.remove("is-valid");
        identificadorInput.classList.add("is-invalid");
    }
});

//calcular tu pierdes
document.addEventListener("DOMContentLoaded", function () {
    const valorIbcBasicoInput = document.getElementById("valor_ibc_basico");
    const numeroDiasInput = document.getElementById("numero_dias");
    const tuPierdesInput = document.getElementById("tu_pierdes");
    const tePagamosInput = document.getElementById("te_pagamos");

    // Función para limpiar el valor IBC y convertirlo a número
    function limpiarValor(valor) {
        // Elimina comas, puntos, o cualquier otro separador de miles, dejando solo los números
        return parseFloat(valor.replace(/[^0-9]/g, ""));
    }

    // Función para calcular "Tu Pierdes"
    function calcularTuPierdes() {
        const valorIbcBasico = limpiarValor(valorIbcBasicoInput.value);
        const numeroDias = parseInt(numeroDiasInput.value);

        // Validar que el valor IBC básico y el número de días sean números válidos
        if (!isNaN(valorIbcBasico) && !isNaN(numeroDias)) {
            // Aplicar la fórmula: Si número de días es menor o igual a 2, no hay cálculo
            if (numeroDias <= 2) {
                tuPierdesInput.value = "";
                tePagamosInput.value = ""; // No se calcula nada si días <= 2
            } else {
                const resultadoTuPierdes =
                    (valorIbcBasico / 90) * (numeroDias - 2);
                tuPierdesInput.value = resultadoTuPierdes.toLocaleString(
                    "es-CO",
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    }
                );
                // Calcular "Te Pagamos" basado en "Tu Pierdes"
                const resultadoTePagamos = resultadoTuPierdes * 1.3;
                tePagamosInput.value = resultadoTePagamos.toLocaleString(
                    "es-CO",
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    }
                );
            }
        } else {
            tuPierdesInput.value = "";
            tePagamosInput.value = "";
        }
    }

    // Agregar eventos para recalcular cuando cambien los valores
    valorIbcBasicoInput.addEventListener("input", calcularTuPierdes);
    numeroDiasInput.addEventListener("input", calcularTuPierdes);
});