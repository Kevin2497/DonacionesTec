function cerrarModal() {
            document.getElementById("modalDonacion").style.display = "none";
        }

document.querySelectorAll(".donar").forEach(link => {
    link.addEventListener("click", function(e) {
        e.preventDefault();
        const nombre = this.getAttribute("data-nombre");
        const descripcion = this.getAttribute("data-descripcion");
        document.getElementById("tituloModal").innerText = "Donar al proyecto de " + nombre;
        document.getElementById("descripcionProyecto").innerText = descripcion;
        document.getElementById("generarDonacionBtn").href = "controlador/generarDonacion.php?id=" + this.getAttribute("data-id");
        document.getElementById("modalDonacion").style.display = "block";
    });
});

document.getElementById("donacionGeneralBtn").addEventListener("click", function() {
    document.getElementById("tituloModal").innerText = "Donación General";
    document.getElementById("descripcionProyecto").innerText = "";
    document.getElementById("generarDonacionBtn").href = "controlador/generarDonacion.php?id=" + this.getAttribute("data-id");
    document.getElementById("modalDonacion").style.display = "block";
});

document.getElementById("donacionGeneralBtn").addEventListener("click", function() {
    document.getElementById("tituloModal").innerText = "Donación General";
    document.getElementById("descripcionProyecto").innerText = "";
    document.getElementById("generarDonacionBtn").href = "controlador/generarDonacion.php?id=" + this.getAttribute("data-id");
    document.getElementById("modalDonacion").style.display = "block";
});
