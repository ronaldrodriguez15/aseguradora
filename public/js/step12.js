// VALOR TOTAL DE DESCUENTO TOTAL
document.addEventListener("DOMContentLoaded", function () {
    const valorIbcBasico = document.getElementById("valor_ibc_basico");
    const descuentoEps = document.getElementById("descuento_eps");
    const deseaValor = document.getElementById("desea_valor");
    const valorAdicional = document.getElementById("valor_adicional");
    const valPrevexequialExclusivo = document.getElementById(
        "val_prevexequial_eclusivo"
    );
    const total = document.getElementById("total");
    const primaPagoPrimaSeguro = document.getElementById(
        "prima_pago_prima_seguro"
    );
    const gastosAdministrativos = document.getElementById(
        "gastos_administrativos"
    );
    const valTotalDescMensual = document.getElementById(
        "val_total_desc_mensual"
    );
    const numeroDias = document.getElementById("numero_dias");
    const tuPierdesInput = document.getElementById("tu_pierdes");
    const tePagamosInput = document.getElementById("te_pagamos");

    // Función para calcular el descuento, valor adicional, total, valor previ-exequial exclusivo, prima de seguro, y valor total de descuento mensual
    function calcularValores() {
        // Obtén el valor IBC básico y realiza el cálculo del descuento
        const ibcBasicoValue = valorIbcBasico.value.replace(/\./g, "");
        const valor = parseFloat(ibcBasicoValue.replace(/[^0-9.-]/g, ""));
        const deseaValorAdicional = deseaValor.value.toLowerCase() === "si";

        // Calcular el descuento
        let descuento = 0;
        if (!isNaN(valor)) {
            descuento = valor * 0.04;
            descuentoEps.value = descuento.toLocaleString("es-CO", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        } else {
            descuentoEps.value = "";
        }

        // Calcular el valor adicional
        let adicional = 0;
        if (deseaValorAdicional && !isNaN(valor)) {
            adicional = valor * 0.3;
            valorAdicional.value = adicional.toLocaleString("es-CO", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        } else {
            valorAdicional.value = "";
        }

        // Calcular el total
        let totalValue = 0;
        if (!isNaN(valor)) {
            totalValue = valor + adicional;
            total.value = totalValue.toLocaleString("es-CO", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        } else {
            total.value = "";
        }

        // Calcular el valor previ-exequial exclusivo
        if (descuento > 0) {
            valPrevexequialExclusivo.value = "2400";
        } else {
            valPrevexequialExclusivo.value = "";
        }

        // Calcular la prima de pago prima de seguro
        let prima;
        if (descuento > 0) {
            if (totalValue <= 2681240) {
                prima = 14000;
            } else {
                const calculo = totalValue * 0.004712 + 1366;
                prima = Math.ceil(calculo / 500) * 500; // Redondea hacia arriba al múltiplo más cercano de 500
            }
            primaPagoPrimaSeguro.value = prima.toLocaleString("es-CO", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        } else {
            primaPagoPrimaSeguro.value = "";
        }

        // Calcular el valor total de descuento mensual
        const gastos = parseFloat(
            gastosAdministrativos.value.replace(/[^0-9.-]/g, "")
        );
        if (!isNaN(totalValue) && !isNaN(prima) && !isNaN(gastos)) {
            const valorTotalDescMensual =
                (parseFloat(
                    valPrevexequialExclusivo.value.replace(/[^0-9.-]/g, "")
                ) || 0) +
                prima +
                gastos;
            valTotalDescMensual.value = valorTotalDescMensual.toLocaleString(
                "es-CO",
                { minimumFractionDigits: 2, maximumFractionDigits: 2 }
            );
        } else {
            valTotalDescMensual.value = "";
        }
    }

    // Calcular en tiempo real mientras se escribe en los campos y se cambia el select
    valorIbcBasico.addEventListener("input", calcularValores);
    deseaValor.addEventListener("change", calcularValores);
    gastosAdministrativos.addEventListener("input", calcularValores);
    valorIbcBasico.addEventListener("input", calcularValores); // Corrección aplicada aquí


    function calcularResultados() {
        const ibcBasicoValue = valorIbcBasico.value.replace(/\./g, "");
        const valor = parseFloat(ibcBasicoValue.replace(/[^0-9.-]/g, ""));
        const dias = parseInt(numeroDias.value, 10);

        // Validar el rango de días
        if (dias < 3 || dias > 45) {
            Swal.fire({
                icon: "warning",
                title: "Número de días fuera de rango",
                text: "Por favor, ingrese un número de días entre 3 y 45.",
                confirmButtonText: "Aceptar",
            });
            return; // Salir de la función si los días están fuera de rango
        }

        // Calcular "Tu Pierdes"
        let tuPierdes = 0;
        if (!isNaN(valor) && !isNaN(dias) && dias > 2) {
            tuPierdes = (valor / 90) * (dias - 2);
        }

        // Mostrar el resultado en el input "Tu Pierdes"
        tuPierdesInput.value = tuPierdes.toLocaleString("es-CO", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        // Calcular "Nosotros Te Pagamos"
        const pagamos = tuPierdes * 1.3;

        // Mostrar el resultado en el input "Nosotros Te Pagamos"
        tePagamosInput.value = pagamos.toLocaleString("es-CO", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    }

    // CONFIRMACION ENVIO DE FORMULARIO
    var form = document.getElementById("formStep1"); // Selecciona tu formulario

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Detiene el envío del formulario inicialmente

        // Obtén los valores de los correos electrónicos
        var email = document.getElementById("email_corporativo").value;
        var emailConfirm = document.getElementById("email_confirmacion").value;

        // Verifica si los correos electrónicos coinciden
        if (email !== emailConfirm) {
            Swal.fire({
                title: "Campos no coinciden",
                text: "Los correos electrónicos no coinciden. Por favor, revisa los campos.",
                icon: "error",
                confirmButtonColor: "#28a745",
                confirmButtonText: "Aceptar",
            });
            // Marca los campos como inválidos
            document
                .getElementById("email_corporativo")
                .classList.add("is-invalid");
            document
                .getElementById("email_confirm")
                .classList.add("is-invalid");
        } else {
            // Muestra la confirmación si los correos coinciden
            Swal.fire({
                title: "¿Estás seguro de continuar?",
                text: "Recuerda que no podrás editar la información una vez continues con el proceso de afiliación",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, continuar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Envía el formulario si el usuario confirma
                }
            });
        }
    });
});