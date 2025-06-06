function abrirPopup() {
  document.getElementById("popup").style.display = "block";
}

function cerrarPopup() {
  document.getElementById("popup").style.display = "none";
}

// Al enviar el formulario
function enviarFormulario(formulario) {
  // Crear un objeto FormData y enviarlo por fetch
  const datos = new FormData(formulario);

  fetch(formulario.action, {
    method: "POST",
    body: datos
  })
  .then(response => response.text())
  .then(res => {
    cerrarPopup();
    setTimeout(() => location.reload(), 500); // recargar tras medio segundo
  })
  .catch(error => alert("Ocurrió un error al actualizar el perfil"));

  return false; // evita que se recargue la página por defecto
}