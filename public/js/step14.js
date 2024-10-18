//-------------------- DEBITO -------------------------

document.getElementById("forma_pago").addEventListener("change", function () {
    var debitoAutomaticoFields = document.getElementById(
        "debito_automatico_fields"
    );
    var tipoCuenta = document.getElementById("tipo_cuenta");
    var noCuenta = document.getElementById("no_cuenta");
    var rNoCuenta = document.getElementById("r_no_cuenta");
    var banco = document.getElementById("banco");
    var ciudadBanco = document.getElementById("ciudad_banco");

    if (this.value === "debito_automatico") {
        // Mostrar los campos para débito automático
        debitoAutomaticoFields.style.display = "block";
        tipoCuenta.setAttribute("required", "true");
        noCuenta.setAttribute("required", "true");
        rNoCuenta.setAttribute("required", "true");
        banco.setAttribute("required", "true");
        ciudadBanco.setAttribute("required", "true");
    } else {
        // Ocultar los campos y quitar el atributo required
        debitoAutomaticoFields.style.display = "none";
        tipoCuenta.removeAttribute("required");
        noCuenta.removeAttribute("required");
        rNoCuenta.removeAttribute("required");
        banco.removeAttribute("required");
        ciudadBanco.removeAttribute("required");
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const noCuenta = document.getElementById("no_cuenta");
    const rNoCuenta = document.getElementById("r_no_cuenta");

    // Función para validar si los campos coinciden
    function validarCuentas() {
        if (noCuenta.value !== rNoCuenta.value) {
            // Si no coinciden, mostrar error
            noCuenta.classList.add("is-invalid");
            rNoCuenta.classList.add("is-invalid");
        } else {
            // Si coinciden, remover error
            noCuenta.classList.remove("is-invalid");
            rNoCuenta.classList.remove("is-invalid");
        }
    }

    // Validar en tiempo real mientras el usuario escribe
    noCuenta.addEventListener("input", validarCuentas);
    rNoCuenta.addEventListener("input", validarCuentas);
});

document.addEventListener("DOMContentLoaded", function () {
    const formaPago = document.getElementById("forma_pago");
    const gastosAdministrativos = document.getElementById("gastos_administrativos");
    const valTotalDescMensual = document.getElementById("val_total_desc_mensual");

    let seSumo1400 = false; // Bandera para controlar si se han sumado los 1400

    // Función para limpiar formato (elimina puntos y convierte a número)
    function limpiarFormato(valor) {
        return parseFloat(valor.replace(/\./g, '').replace(',', '.')) || 0;
    }

    // Función para formatear números con puntos como separador de miles
    function formatearConPuntos(valor) {
        return valor.toLocaleString('de-DE');
    }

    formaPago.addEventListener("change", function () {
        let valorTotal = limpiarFormato(valTotalDescMensual.value); // Limpiar formato del valor

        if (this.value === "debito_automatico" && !seSumo1400) {
            gastosAdministrativos.value = 1400;
            gastosAdministrativos.readOnly = true;
            valorTotal += 1400; // Sumar 1.400
            valTotalDescMensual.value = formatearConPuntos(valorTotal); // Aplicar formato
            seSumo1400 = true;
        } else if (this.value === "mensual_libranza" && seSumo1400) {
            gastosAdministrativos.value = 0;
            gastosAdministrativos.readOnly = true;
            valorTotal -= 1400; // Restar 1.400
            valTotalDescMensual.value = formatearConPuntos(valorTotal); // Aplicar formato
            seSumo1400 = false; // Marcar que los 1400 se han restado
        } else if (this.value !== "debito_automatico" && this.value !== "mensual_libranza") {
            gastosAdministrativos.value = 0;
            gastosAdministrativos.readOnly = true;
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const noIdentificacion = document.getElementById("no_identificacion");
    const confirmNoIdentificacion = document.getElementById("confirm_no_identificacion");

    // Función para formatear el número con puntos al escribir
    function formatearConPuntos(valor) {
        return valor.replace(/\D/g, "") // Elimina todo excepto dígitos
            .replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Agrega los puntos para miles
    }

    // Escuchar los eventos de entrada para formatear el campo
    noIdentificacion.addEventListener("input", function () {
        const valorFormateado = formatearConPuntos(this.value);
        this.value = valorFormateado;
        validarIdentificaciones();
    });

    confirmNoIdentificacion.addEventListener("input", function () {
        const valorFormateado = formatearConPuntos(this.value);
        this.value = valorFormateado;
        validarIdentificaciones();
    });

    // Función para validar si ambos campos coinciden
    function validarIdentificaciones() {
        const noIdentificacionValor = noIdentificacion.value.replace(/\./g, ''); // Eliminar puntos
        const confirmNoIdentificacionValor = confirmNoIdentificacion.value.replace(/\./g, ''); // Eliminar puntos

        if (noIdentificacionValor !== "" && confirmNoIdentificacionValor !== "") {
            if (noIdentificacionValor === confirmNoIdentificacionValor) {
                noIdentificacion.classList.add("is-valid");
                noIdentificacion.classList.remove("is-invalid");
                confirmNoIdentificacion.classList.add("is-valid");
                confirmNoIdentificacion.classList.remove("is-invalid");
            } else {
                noIdentificacion.classList.add("is-invalid");
                noIdentificacion.classList.remove("is-valid");
                confirmNoIdentificacion.classList.add("is-invalid");
                confirmNoIdentificacion.classList.remove("is-valid");
            }
        }
    }

    // Bloquear copiar/pegar en el campo de confirmación
    confirmNoIdentificacion.addEventListener('paste', function (e) {
        e.preventDefault(); // Evita que se pegue contenido
    });
    confirmNoIdentificacion.addEventListener('copy', function (e) {
        e.preventDefault(); // Evita que se copie contenido
    });
});