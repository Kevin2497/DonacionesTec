<?php
require_once('../modelo/consultasDonaciones.php');

if (!isset($_GET['id'])) {
    echo "ID no proporcionado.";
    exit;
}

$idDonacion = $_GET['id'];

conexionBDD();
$datos=obtenerDetalleMonetariaF($idDonacion);

if ($datos->num_rows > 0) {
    $fila = $datos->fetch_assoc();
    echo "<h2>Detalle de Donación Monetaria</h2>";
    echo "<p><strong>Monto:</strong> $" . htmlspecialchars($fila['monto']) . "</p>";
    echo "<p><strong>Método de Pago:</strong> " . htmlspecialchars($fila['metodoPago']) . "</p>";

    if (!empty($fila['comprobante'])) {
        echo "<p><strong>Comprobante PDF:</strong> <a href='comprobantes/" .urlencode($fila['comprobante']) . "' target='_blank'>Ver comprobante</a></p>";
    } else {
        echo "<button onclick='generarComprobantePDF($idDonacion)'>Generar Comprobante PDF</button>";
    }
} else {
    echo "<p>No se encontró información para esta donación.</p>";
}


?>
