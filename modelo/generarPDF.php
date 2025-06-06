<?php
require_once("../controlador/tcpdf/tcpdf.php");

$conexion = new mysqli("localhost", "root", "", "donacionesTec", 3307);
if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta Top Donantes
$sqlTopDonantes = "SELECT u.nombre, u.correo,
    COALESCE(SUM(dm.monto), 0) AS total_monetario,
    COALESCE(SUM(de.cantidad), 0) AS total_especie,
    COALESCE(COUNT(DISTINCT dp.idDonacion), 0) AS total_proyectos,
    (
        COALESCE(SUM(dm.monto), 0) + 
        COALESCE(SUM(de.cantidad), 0) + 
        COALESCE(COUNT(DISTINCT dp.idDonacion), 0)
    ) AS total_general
    FROM usuarios u
    LEFT JOIN donaciones d ON u.idUsuario = d.idUsuario
    LEFT JOIN donacionesmonetarias dm ON d.idDonacion = dm.idDonacion
    LEFT JOIN donacionesespecie de ON d.idDonacion = de.idDonacion
    LEFT JOIN donacionespropuesta dp ON d.idDonacion = dp.idDonacion
    GROUP BY u.idUsuario
    ORDER BY total_general DESC
    LIMIT 10";

$resTop = $conexion->query($sqlTopDonantes);
if (!$resTop) {
    die("Error SQL Donantes: " . $conexion->error);
}

// Consulta Últimos Usuarios
$sqlUltimos = "SELECT nombre, correo FROM usuarios ORDER BY idUsuario DESC LIMIT 10";
$resUltimos = $conexion->query($sqlUltimos);
if (!$resUltimos) {
    die("Error SQL Usuarios: " . $conexion->error);
}

// Crear el PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Título
$pdf->Cell(0, 10, 'Reporte de Estadísticas - DonacionesTec', 0, 1, 'C');
$pdf->Ln(5);

// Sección: Máximos Donantes
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Máximos Donantes', 0, 1);
$pdf->SetFont('helvetica', '', 10);

$html = '<table border="1" cellpadding="4">
<tr style="font-weight: bold; background-color: #f0f0f0;">
    <td>Nombre</td><td>Correo</td><td>Monetario</td><td>Productos</td><td>Proyectos</td><td>Total</td>
</tr>';

while ($row = $resTop->fetch_assoc()) {
    $html .= '<tr>
        <td>' . $row["nombre"] . '</td>
        <td>' . $row["correo"] . '</td>
        <td>$' . $row["total_monetario"] . '</td>
        <td>' . $row["total_especie"] . '</td>
        <td>' . $row["total_proyectos"] . '</td>
        <td><strong>$' . $row["total_general"] . '</strong></td>
    </tr>';
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Espacio
$pdf->Ln(10);

// Sección: Últimos Usuarios
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Últimos Usuarios Registrados', 0, 1);
$pdf->SetFont('helvetica', '', 10);

$html2 = '<table border="1" cellpadding="4">
<tr style="font-weight: bold; background-color: #f0f0f0;">
    <td>Nombre</td><td>Correo</td>
</tr>';

while ($row = $resUltimos->fetch_assoc()) {
    $html2 .= '<tr>
        <td>' . $row["nombre"] . '</td>
        <td>' . $row["correo"] . '</td>
    </tr>';
}
$html2 .= '</table>';
$pdf->writeHTML($html2, true, false, true, false, '');

// Agregar la gráfica si fue enviada
if (isset($_POST['graficaBase64'])) {
    $img = $_POST['graficaBase64'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $imgData = base64_decode($img);
    $rutaImagen = tempnam(sys_get_temp_dir(), 'grafica') . '.png';
    file_put_contents($rutaImagen, $imgData);

    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Gráfica de Crecimiento de Usuarios', 0, 1);
    $pdf->Image($rutaImagen, '', '', 160, 90, 'PNG');

    unlink($rutaImagen);
}
// Mostrar PDF
$pdf->Output('reporte_estadisticas.pdf', 'I');
?>
