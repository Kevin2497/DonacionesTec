document.addEventListener("DOMContentLoaded", function () {
    const botones = document.querySelectorAll(".ver-perfil");
    const modal = document.getElementById("perfilModal");
    const contenido = document.getElementById("contenidoPerfil");
    const cerrar = document.getElementById("cerrarModal");

    botones.forEach(boton => {
        boton.addEventListener("click", function (e) {
            e.preventDefault();
            const id = this.getAttribute("data-id");

            fetch(`verPerfil.php?id=${id}`)
                .then(res => res.text())
                .then(data => {
                    contenido.innerHTML = data;
                    modal.style.display = "block";
                });
        });
    });

    cerrar.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
});

