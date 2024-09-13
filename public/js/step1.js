document.getElementById("aseguradora").addEventListener("change", function () {
    // Obtener la opción seleccionada
    var selectedOption = this.options[this.selectedIndex];

    // Obtener el número de póliza de los datos de la opción seleccionada
    var noPoliza = selectedOption.getAttribute("data-poliza");

    const polizaInput = document.getElementById("no_poliza");

    // Asignar el número de póliza al campo correspondiente
    polizaInput.value = noPoliza;

    // Limpiar clases previas
    polizaInput.classList.remove("is-valid", "is-invalid");

    // Añadir la clase correcta dependiendo de la edad
    if (polizaInput.value === "") {
        polizaInput.classList.add("is-invalid");
    } else {
        polizaInput.classList.add("is-valid");
    }
});

document.getElementById("asesor_code").addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];

    const asesorName = selectedOption.getAttribute("data-name");
    const asesorInput = document.getElementById("nombre_asesor");

    asesorInput.value = asesorName ? asesorName : "";

    asesorInput.classList.remove("is-valid", "is-invalid");

    if (asesorName === null) {
        asesorInput.classList.add("is-invalid");
    } else {
        asesorInput.classList.add("is-valid");
    }
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

// Calculo de TU PIERDES!!! y NOSOTROS TE PAGAMOS!!!

function calcularPerdida() {
    // Obtener los valores de los inputs
    var numeroDias = parseFloat(document.getElementById("numero_dias").value);
    var valorIBC = parseFloat(
        document.getElementById("valor_ibc_basico").value
    );

    // Verificar que los valores sean válidos
    if (isNaN(numeroDias) || isNaN(valorIBC) || numeroDias <= 2) {
        document.getElementById("resultadoPierdes").innerText = "$0";
        document.getElementById("resultadoPierdesInput").value = "0";
    } else {
        // Realizar el cálculo: = (valorIBC / 90) * (numeroDias - 2)
        var resultado = (valorIBC / 90) * (numeroDias - 2);

        // Redondear el resultado y formatear como valor monetario en COP sin decimales
        var resultadoFormateado = Math.round(resultado).toLocaleString(
            "es-CO",
            {
                style: "currency",
                currency: "COP",
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }
        );

        // Mostrar el resultado en el modal
        document.getElementById("resultadoPierdes").innerText =
            resultadoFormateado;

        // Actualizar el campo oculto con el valor numérico
        document.getElementById("resultadoPierdesInput").value =
            Math.round(resultado);
    }

    // Hacer visible el botón "NOSOTROS TE PAGAMOS!!!"
    document.getElementById("botonTePagamos").style.display = "flex";
}

function calcularPagamos() {
    // Obtener los valores de los inputs
    var numeroDias = parseFloat(document.getElementById("numero_dias").value);
    var resultadoPierdes = parseFloat(
        document.getElementById("resultadoPierdesInput").value
    );

    // Verificar que los valores sean válidos
    if (isNaN(numeroDias) || isNaN(resultadoPierdes) || numeroDias <= 2) {
        document.getElementById("resultadoPagamos").innerText = "$0";
        document.getElementById("resultadoPagamosInput").value = "0";
    } else {
        // Realizar el cálculo: = resultadoPierdes * 1.3
        var resultado = resultadoPierdes * 1.3;

        // Redondear el resultado y formatear como valor monetario en COP sin decimales
        var resultadoFormateado = Math.round(resultado).toLocaleString(
            "es-CO",
            {
                style: "currency",
                currency: "COP",
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }
        );

        // Mostrar el resultado en el modal
        document.getElementById("resultadoPagamos").innerText =
            resultadoFormateado;

        // Actualizar el campo oculto con el valor numérico
        document.getElementById("resultadoPagamosInput").value =
            Math.round(resultado);
    }
}

function verificarCampos() {
    var numeroDias = parseFloat(
        document.getElementById("numero_dias").value.trim()
    );
    var valorIBC = parseFloat(
        document.getElementById("valor_ibc_basico").value.trim()
    );

    var esNumeroDiasValido = !isNaN(numeroDias) && numeroDias > 0;
    var esValorIBCValido = !isNaN(valorIBC) && valorIBC > 0;

    // Habilitar o deshabilitar el botón basado en las verificaciones (TU PIERDES)
    var botonTuPierdes = document.getElementById("botonTuPierdes");
    if (esNumeroDiasValido && esValorIBCValido) {
        botonTuPierdes.style.display = "flex"; // Habilitar el botón
    } else {
        botonTuPierdes.style.display = "none"; // Deshabilitar el botón
    }
}

// Añadir event listeners para verificar los campos en tiempo real
document
    .getElementById("numero_dias")
    .addEventListener("input", verificarCampos);
document
    .getElementById("valor_ibc_basico")
    .addEventListener("input", verificarCampos);

// Ejecutar la función al cargar la página
window.onload = verificarCampos;

// LOGICA PARA LOS CAMPOS DE VALORES ADICIONALES
document.addEventListener("DOMContentLoaded", function () {
    var selectField = document.getElementById("desea_valor");
    var valorAdicionalField = document.getElementById("valor_adicional");
    var valorIbcBasicoField = document.getElementById("valor_ibc_basico");
    var totalField = document.getElementById("total");

    // Función para habilitar o deshabilitar el campo de valor adicional
    function toggleValorAdicional() {
        if (selectField.value === "si") {
            valorAdicionalField.disabled = false;
            valorAdicionalField.value = ""; // Limpia el valor cuando se habilita
        } else {
            valorAdicionalField.disabled = true;
            valorAdicionalField.value = "0"; // Establece el valor en 0 cuando se deshabilita
        }
    }

    // Inicializa el estado del campo "Valor adicional" según la selección actual
    toggleValorAdicional();

    // Agrega un event listener al select para manejar cambios
    selectField.addEventListener("change", toggleValorAdicional);

    // Función para calcular y actualizar el campo "Total"
    function actualizarTotal() {
        var valorIbcBasico =
            parseFloat(valorIbcBasicoField.value.replace(/[^0-9.]/g, "")) || 0;
        var valorAdicional =
            parseFloat(valorAdicionalField.value.replace(/[^0-9.]/g, "")) || 0;

        // Calcula el total
        var total = valorIbcBasico + valorAdicional;

        // Actualiza el campo "Total"
        totalField.value = total.toLocaleString("en-US"); // Muestra el número con formato de miles
    }

    // Inicializa el campo "Total" al cargar la página
    actualizarTotal();

    // Agrega event listeners a los campos para actualizar el total cuando cambien
    valorIbcBasicoField.addEventListener("input", actualizarTotal);
    valorAdicionalField.addEventListener("input", actualizarTotal);
});

document.addEventListener("DOMContentLoaded", function () {
    var descuentoEpsField = document.getElementById("descuento_eps");
    var totalField = document.getElementById("total");
    var valAdicionalField = document.getElementById("valor_adicional");

    function limpiarValor(valor) {
        return parseFloat(
            valor
                .replace(/,/g, "") // Elimina las comas
                .replace(/\./g, ".") // Asegura que los puntos se mantengan como decimales
        );
    }

    function calcularPrimaPago() {
        var descuentoEPS = limpiarValor(descuentoEpsField.value);
        var valorTotal = limpiarValor(totalField.value);

        if (!isNaN(descuentoEPS) && !isNaN(valorTotal)) {
            var resultado = "";

            if (valorTotal <= 2681240) {
                resultado = 14000;
            } else {
                resultado = valorTotal * 0.004712 + 1366;
                // Redondear al múltiplo superior más cercano de 500
                resultado = Math.ceil(resultado / 500) * 500;
            }

            // Formatear el resultado como pesos colombianos
            document.getElementById("prima_pago_prima_seguro").value =
                resultado.toLocaleString("es-CO", {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                });
        } else {
            document.getElementById("prima_pago_prima_seguro").value = "";
        }
    }

    // Agrega event listeners a los campos para actualizar la prima cuando cambien
    descuentoEpsField.addEventListener("input", calcularPrimaPago);
    totalField.addEventListener("input", calcularPrimaPago);
    valAdicionalField.addEventListener("input", calcularPrimaPago);
});

// VALOR TOTAL DE DESCUENTO TOTAL
document.addEventListener("DOMContentLoaded", function () {
    var valPrevexequialField = document.getElementById(
        "val_prevexequial_eclusivo"
    );
    var primaPagoField = document.getElementById("prima_pago_prima_seguro");
    var gastosAdministrativosField = document.getElementById(
        "gastos_administrativos"
    );
    var totalDescuentoField = document.getElementById("val_total_desc_mensual");
    var valAdicionalField = document.getElementById("valor_adicional");

    function limpiarValor(valor) {
        return parseFloat(
            valor
                .replace(/\./g, "") // Elimina los puntos
                .replace(/,/g, ".") // Reemplaza las comas con puntos
        );
    }

    function calcularTotalDescuento() {
        var valPrevexequial = limpiarValor(valPrevexequialField.value) || 0;
        var primaPago = limpiarValor(primaPagoField.value) || 0;
        var gastosAdministrativos =
            limpiarValor(gastosAdministrativosField.value) || 0;

        // Calcula el total sumando los tres valores
        var totalDescuento =
            valPrevexequial + primaPago + gastosAdministrativos;

        // Formatear el resultado como pesos colombianos y mostrarlo en el campo correspondiente
        totalDescuentoField.value = totalDescuento.toLocaleString("es-CO", {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        });
    }

    // Agrega event listeners a los campos que intervienen en el cálculo
    gastosAdministrativosField.addEventListener(
        "input",
        calcularTotalDescuento
    );

    // Si "prima_pago_prima_seguro" se actualiza dinámicamente, llama la función después de su cálculo
    primaPagoField.addEventListener("input", calcularTotalDescuento);
    valAdicionalField.addEventListener("input", calcularTotalDescuento);
    gastosAdministrativosField.addEventListener(
        "input",
        calcularTotalDescuento
    );

    // CONFIRMACION ENVIO DE FORMULARIO
    var form = document.getElementById("formStep1"); // Selecciona tu formulario

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Detiene el envío del formulario inicialmente

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
    });
});
