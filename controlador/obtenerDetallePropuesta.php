<?php
require_once('../modelo/consultasDonaciones.php');

$id = $_GET['id'];

conexionBDD();

$datos = obtenerDetallePropuestaF($id); 

if ($row = $datos->fetch_assoc()) {
    echo "<strong>Descripción:</strong> " . htmlspecialchars($row['descripcion']) . "<br>";

    if (!empty($row['archivo'])) {
        echo "<strong>Archivo:</strong> <a href='uploads/archivos/" . htmlspecialchars($row['archivo']) . "' target='_blank'>Ver archivo</a><br>";
    }

    if (!empty($row['comprar'])) {
        echo "<strong>¿Comprar?:</strong> " . htmlspecialchars($row['comprar']) . "<br>";
    }

    echo "<form method='post' action='actualizarEstadoPropuesta.php'>
        <input type='hidden' name='idDonacion' value='" . $id . "'>
        <button type='submit' name='accion' value='aprobado'>Aprobar</button>
        <button type='submit' name='accion' value='rechazado'>Rechazar</button>
    </form>";
} else {
    echo "No se encontró la donación por propuesta.";
}
?>
