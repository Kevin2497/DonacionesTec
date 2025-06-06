function agregarBuscador(inputId, tablaClase) {
    document.getElementById(inputId).addEventListener("input", function () {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll(`.${tablaClase} tbody tr`);
        filas.forEach(fila => {
            const nombre = fila.cells[0].textContent.toLowerCase();
            fila.style.display = nombre.includes(filtro) ? "" : "none";
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    agregarBuscador("buscador1", "tabla1");
    agregarBuscador("buscador2", "tabla2");
    agregarBuscador("buscador3", "tabla3");
});
