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

// Ejecutar el script al cargar la página para mostrar los campos correctos por defecto
document.getElementById("forma_pago").dispatchEvent(new Event("change"));

const noCuenta = document.getElementById("no_cuenta");
const rNoCuenta = document.getElementById("r_no_cuenta");
const submitBtn = document.getElementById("submitBtn");

// Función para validar los números de cuenta
function validarCuentas() {
    if (noCuenta.value !== rNoCuenta.value) {
        noCuenta.classList.add("is-invalid");
        rNoCuenta.classList.add("is-invalid");
        return false; // Las cuentas no coinciden
    } else {
        noCuenta.classList.remove("is-invalid");
        rNoCuenta.classList.remove("is-invalid");
        return true; // Las cuentas coinciden
    }
}

// CONFIRMACION ENVIO DE FORMULARIO
var form = document.getElementById("formStep2"); // Selecciona tu formulario

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
