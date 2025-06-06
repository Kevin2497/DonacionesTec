function cargarDetalle(tipo, id) {
    let archivo = "";
    switch (tipo) {
        case "monetaria":
            archivo = "controlador/obtenerDetalleMonetaria.php";
            break;
        case "especie":
            archivo = "controlador/obtenerDetalleEspecie.php";
            break;
        case "propuesta":
            archivo = "controlador/obtenerDetallePropuesta.php";
            break;
    }

    fetch(`${archivo}?id=${id}`)
        .then(res => res.text())
        .then(html => {
            const modal = document.getElementById("modalDetalle");
            document.getElementById("contenidoModal").innerHTML = html;
            modal.style.display = "block";
        });
}

// Cierra modal
document.getElementById("cerrarModal").addEventListener("click", () => {
    document.getElementById("modalDetalle").style.display = "none";
});

// Asignación a botones
document.querySelectorAll(".ver-mas-btn").forEach(btn => {
    btn.addEventListener("click", function() {
        const tipo = this.getAttribute("data-tipo");
        const id = this.getAttribute("data-id");
        cargarDetalle(tipo, id);
    });
});

function generarComprobantePDF(id) {
    if (!confirm("¿Deseas generar el comprobante PDF ahora?")) return;

    fetch(`controlador/generarComprobante.php?id=${id}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById("contenidoModal").innerHTML = html;
        })
        .catch(err => {
            alert("Ocurrió un error al generar el comprobante.");
            console.error(err);
        });
}
