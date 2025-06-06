<?php
require_once('../tcpdf/tcpdf.php');
require_once('consultasDonaciones.php');

// Validar ID
if (!isset($_GET['id'])) {
    die("ID no proporcionado.");
}

$idDonacion = $_GET['id'];

conexionBDD();

// Obtener datos
$datos = obtenerDatosDonacionMonetaria($idDonacion);


$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Contenido del PDF
$html = "
<h2>Comprobante de Donación Monetaria</h2>
<p><strong>Nombre del donante:</strong> " . htmlspecialchars($datos['nombre']) . "</p>
<p><strong>Monto donado:</strong> $" . htmlspecialchars($datos['monto']) . "</p>
<p><strong>Método de pago:</strong> " . htmlspecialchars($datos['metodoPago']) . "</p>
<p><strong>Fecha de emisión:</strong> " . date('d/m/Y') . "</p>
";

$pdf->writeHTML($html, true, false, true, false, '');

// Guardar el PDF
$nombreArchivo = "comprobante_donacion_{$idDonacion}.pdf";
$ruta = "../comprobantes/" . $nombreArchivo;
$pdf->Output($ruta, 'F'); // 'F' guarda el archivo en disco

// Actualizar nombre del archivo en la base de datos
actualizarNombreComprobante($idDonacion, $nombreArchivo);

echo "Comprobante generado correctamente: <a href='../comprobantes/$nombreArchivo' target='_blank'>Ver PDF</a>";
?>
