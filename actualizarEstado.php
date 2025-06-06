<?php
require_once('../modelo/consultasDonaciones.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idDonacion = $_POST["idDonacion"];
    $accion = $_POST["accion"]; // 'aprobado' o 'rechazado'

    // Validación básica
    if (!in_array($accion, ["aprobado", "rechazado"])) {
        die("Acción no válida.");
    }

    $conexion=conexionBDD();

    // Actualizar el estado de la donación en la tabla 'donaciones'
    $stmt = $conexion->prepare("UPDATE donaciones SET estado = ? WHERE idDonacion = ?");
    if (!$stmt) {
        die("Error en prepare: " . $conexion->error);
    }

    $stmt->bind_param("si", $accion, $idDonacion);
    if ($stmt->execute()) {
        // Redireccionar o mostrar mensaje
        header("Location: donaciones.php?mensaje=actualizado");
        exit;
    } else {
        echo "Error al actualizar el estado.";
    }
}
?>
