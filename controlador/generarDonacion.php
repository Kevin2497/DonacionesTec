<?php
session_start();
require_once('tcpdf/tcpdf.php');

// Conexión
$conn = new mysqli("localhost", "root", "", "donacionesTec", 3307);
if ($conn->connect_errno) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_SESSION["usuario"])) {
    die("Sesión expirada. Inicia sesión.");
}

include_once("../modelo/Usuario.php");
$usuario = unserialize($_SESSION["usuario"]);
$idUsuario = $usuario->getIdUsuario();

// Verifica si viene un proyecto específico
$esProyecto = isset($_GET['idProyecto']);
$tipoDonacion = $esProyecto ? "propuesta" : "general";
$descripcion = "Donación general";

// Si es proyecto, obtener descripción real del proyecto
if ($esProyecto) {
    $idProponente = $_GET['idProyecto'];

    $stmtProyecto = $conn->prepare("SELECT dp.descripcion FROM donacionespropuesta dp 
        INNER JOIN donaciones d ON dp.idDonacion = d.idDonacion 
        WHERE d.idUsuario = ? ORDER BY d.idDonacion DESC LIMIT 1");

    $stmtProyecto->bind_param("i", $idProponente);
    $stmtProyecto->execute();
    $resultado = $stmtProyecto->get_result();

    if ($resultado && $row = $resultado->fetch_assoc()) {
        $descripcion = $row['descripcion'];
    } else {
        $descripcion = "Donación al proyecto del usuario ID $idProponente";
    }
}

// Insertar en donaciones
$stmt = $conn->prepare("INSERT INTO donaciones (idUsuario, tipoDonacion, estado) VALUES (?, ?, 'pendiente')");
$stmt->bind_param("is", $idUsuario, $tipoDonacion);
$stmt->execute();
$idDonacion = $conn->insert_id;

// Si es de proyecto, insertar también en donacionespropuesta
if ($esProyecto) {
    $stmt2 = $conn->prepare("INSERT INTO donacionespropuesta (idDonacion, descripcion) VALUES (?, ?)");
    $stmt2->bind_param("is", $idDonacion, $descripcion);
    $stmt2->execute();
}

// GENERAR PDF DE FICHA
$pdf = new TCPDF();
$pdf->AddPage();

// Simula código de barras y datos
$referencia = "D" . str_pad($idDonacion, 6, "0", STR_PAD_LEFT);
$codigoBarras = $referencia;

// Diseño del contenido
$html = "
<h2 style='text-align:center;'>Ficha de Donación</h2>
<hr>
<p><strong>Nombre:</strong> " . htmlspecialchars($usuario->getNombre()) . "</p>
<p><strong>Tipo de Donación:</strong> " . ucfirst($tipoDonacion) . "</p>
<p><strong>Descripción:</strong> $descripcion</p>
<p><strong>Referencia:</strong> $referencia</p>
<br><br>
<p style='text-align:center;'>Presenta esta ficha al momento de realizar tu donación.</p>
";

// Imprime HTML
$pdf->writeHTML($html, true, false, true, false, '');

// Código de barras
$pdf->write2DBarcode($codigoBarras, 'QRCODE,H', 150, 30, 40, 40, [], 'N');

// Salida como descarga automática
$pdf->Output("FichaDonacion_$referencia.pdf", 'D');
exit;
