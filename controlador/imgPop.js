document.getElementById("foto").addEventListener("change", function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById("previewImagen");

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
        preview.style.display = "none";
    }
});

