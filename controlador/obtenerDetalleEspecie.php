<?php
require_once('../modelo/consultasDonaciones.php');

$id = $_GET['id'];

conexionBDD();

$datos=obtenerDetalleEspecieF($id);

if ($row = $datos->fetch_assoc()) {
    echo "<strong>Descripción:</strong> " . htmlspecialchars($row['descripcion']) . "<br>";
    echo "<strong>Cantidad:</strong> " . htmlspecialchars($row['cantidad']) . "<br>";

    if (!empty($row['foto'])) {
        echo "<strong>Foto:</strong><br><img src='uploads/fotos/" . htmlspecialchars($row['foto']) . "' width='100'><br>";
    }

    if (!empty($row['comprobante'])) {
        echo "<strong>Comprobante:</strong> <a href='comprobantes/" . htmlspecialchars($row['comprobante']) . "' target='_blank'>Ver PDF</a><br>";
    }

    echo "<strong>Estado:</strong> " . htmlspecialchars($row['estado']) . "<br><br>";

    echo "<form method='post' action='actualizarEstado.php'>
        <input type='hidden' name='idDonacion' value='" . $id . "'>
        <button type='submit' name='accion' value='aprobado'>Aprobar</button>
        <button type='submit' name='accion' value='rechazado'>Rechazar</button>
    </form>";
} else {
    echo "No se encontró la donación en especie.";
}
?>
