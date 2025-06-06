function cerrarModal2() {
            document.getElementById("modalDonacionEspecie").style.display = "none";
        }

        document.querySelectorAll(".donar2").forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault  ();
                const nombre = this.getAttribute("data-nombre");
                const descripcion = this.getAttribute("data-descripcion");
                document.getElementById("tituloModalEspecie").innerText = "Donar al proyecto de " + nombre;
                document.getElementById("descripcionProyectoEspecie").innerText = descripcion;
                document.getElementById("generarDonacionBtnEspecie").href = "generarDonacion.php?idProyecto=" + this.getAttribute("data-id");
                document.getElementById("modalDonacionEspecie").style.display = "block";
            });
        });

        document.getElementById("donacionEspecieBtn").addEventListener("click", function() {
            document.getElementById("tituloModalEspecie").innerText = "Donación en Especie";
            document.getElementById("descripcionProyectoEspecie").innerText = "";
            document.getElementById("generarDonacionBtnEspecie").href = "generarDonacion.php";
            document.getElementById("modalDonacionEspecie").style.display = "block";
        });