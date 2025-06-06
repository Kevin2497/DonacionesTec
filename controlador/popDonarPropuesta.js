function cerrarModal3() {
            document.getElementById("modalDonacionPropuesta").style.display = "none";
}

        document.querySelectorAll(".donar3").forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault  ();
                const nombre = this.getAttribute("data-nombre");
                const descripcion = this.getAttribute("data-descripcion");
                document.getElementById("tituloModalPropuesta").innerText = "Donar al proyecto de " + nombre;
                document.getElementById("descripcionProyectoPropuesta").innerText = descripcion;
                document.getElementById("generarDonacionBtnPropuesta").href = "generarDonacionPropuesta.php?idProyecto=" + this.getAttribute("data-id");
                document.getElementById("modalDonacionPropuesta").style.display = "block";
            });
        });

        document.getElementById("donacionPropuestaBtn").addEventListener("click", function() {
            document.getElementById("tituloModalPropuesta").innerText = "Donación de propuesta";
            document.getElementById("descripcionProyectoPropuesta").innerText = "";
            document.getElementById("generarDonacionBtnPropuesta").href = "generarDonacionPropuesta.php";
            document.getElementById("modalDonacionPropuesta").style.display = "block";
        });