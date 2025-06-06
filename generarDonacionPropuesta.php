<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

include_once("modelo/Usuario.php");
$oUsu = unserialize($_SESSION["usuario"]);
$idUsuario = $oUsu->getIdUsuario();

$conexion = new mysqli("localhost", "root", "", "donacionesTec", 3307);
if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Validar entrada
$descripcion = $_POST['descripcion'] ?? '';
if (empty($descripcion)) {
    die("La descripción es obligatoria para una propuesta.");
}

// 1. Insertar en donaciones
$fecha = date('Y-m-d');
$tipoDonacion = 'propuesta';
$estadoDonacion = 'pendiente';

$stmt = $conexion->prepare("INSERT INTO donaciones (idUsuario, tipoDonacion, fecha, estado) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $idUsuario, $tipoDonacion, $fecha, $estadoDonacion);

if (!$stmt->execute()) {
    die("Error al registrar donación: " . $stmt->error);
}

$idDonacion = $conexion->insert_id;

// 2. Insertar en donacionespropuesta
$stmt2 = $conexion->prepare("INSERT INTO donacionespropuesta (idDonacion, descripcion) VALUES (?, ?)");
$stmt2->bind_param("is", $idDonacion, $descripcion);

if (!$stmt2->execute()) {
    die("Error al registrar detalles de propuesta: " . $stmt2->error);
}

echo "<script>alert('Donación por propuesta registrada exitosamente.'); window.location.href='donacionesUsuario.php';</script>";
?>

