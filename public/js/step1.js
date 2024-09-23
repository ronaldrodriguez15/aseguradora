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
    const resultadoPierdes = document.getElementById("resultadoPierdes");
    const resultadoPagamos = document.getElementById("resultadoPagamos");
    const resultadoPierdesInput = document.getElementById(
        "resultadoPierdesInput"
    );
    const resultadoPagamosInput = document.getElementById(
        "resultadoPagamosInput"
    );
    const botonTePagamos = document.getElementById("botonTePagamos");
    const modal = new bootstrap.Modal(
        document.getElementById("tePagamosModal")
    );

    // Función para calcular el descuento, valor adicional, total, valor previ-exequial exclusivo, prima de seguro, y valor total de descuento mensual
    function calcularValores() {
        // Obtén el valor IBC básico y realiza el cálculo del descuento
        const ibcBasicoValue = valorIbcBasico.value.replace(/\./g, '');
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

    // Vincula la función al evento click del botón
    botonTePagamos.addEventListener("click", calcularResultados);

    function calcularResultados() {
        const ibcBasicoValue = valorIbcBasico.value.replace(/\./g, '');
        const valor = parseFloat(ibcBasicoValue.replace(/[^0-9.-]/g, ""));
        const dias = parseInt(numeroDias.value, 10);

        // Validar el rango de días
        if (dias < 3 || dias > 45) {
            Swal.fire({
                icon: "warning",
                title: "Número de días fuera de rango",
                text: "Por favor, ingrese un número de días entre 3 y 45.",
                confirmButtonText: "Aceptar",
            }).then(() => {
                // Si el número de días está fuera del rango, no se abre el modal.
                return; // Salir de la función para evitar el cálculo y la apertura del modal
            });
        }

        // Calcular "Tu Pierdes"
        let tuPierdes = 0;
        if (!isNaN(valor) && !isNaN(dias) && dias > 2) {
            tuPierdes = (valor / 90) * (dias - 2);
        }
        resultadoPierdes.textContent = tuPierdes.toLocaleString("es-CO", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        // Calcular "Nosotros Te Pagamos"
        const pagamos = tuPierdes * 1.3;
        resultadoPagamos.textContent = pagamos.toLocaleString("es-CO", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        // Almacenar los resultados en los campos ocultos
        resultadoPierdesInput.value = tuPierdes.toFixed(2);
        resultadoPagamosInput.value = pagamos.toFixed(2);
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

document.getElementById('valor_ibc_basico').addEventListener('input', function (e) {
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
    e.target.value = new Intl.NumberFormat('es-CO', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(inputNumber);
});

//campo identificador
document.getElementById('aseguradora').addEventListener('change', function () {
    // Get the selected option
    var selectedOption = this.options[this.selectedIndex];

    // Get the value of the 'data-identificador' attribute
    var identificador = selectedOption.getAttribute('data-identificador');

    // Set the value of the 'identificador' field
    var identificadorInput = document.getElementById('identificador');
    identificadorInput.value = identificador || '';

    // Apply or remove 'is-valid' class based on the field value
    if (identificadorInput.value) {
        identificadorInput.classList.add('is-valid');
        identificadorInput.classList.remove('is-invalid');
    } else {
        identificadorInput.classList.remove('is-valid');
        identificadorInput.classList.add('is-invalid');
    }
});
