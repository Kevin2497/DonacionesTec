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
$cantidad = $_POST['cantidad'] ?? 0;
$estado = $_POST['estado'] ?? '';
$foto = $_FILES['foto'] ?? null;

if (empty($descripcion) || empty($cantidad) || empty($estado) || !$foto) {
    die("Todos los campos son obligatorios.");
}

// 1. Insertar en donaciones
$fecha = date('Y-m-d');
$tipoDonacion = 'especie';
$estadoDonacion = 'pendiente';

$stmt = $conexion->prepare("INSERT INTO donaciones (idUsuario, tipoDonacion, fecha, estado) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $idUsuario, $tipoDonacion, $fecha, $estadoDonacion);

if (!$stmt->execute()) {
    die("Error al registrar donación: " . $stmt->error);
}

$idDonacion = $conexion->insert_id; // Obtener el idDonacion recién insertado

// 2. Guardar foto
$nombreFoto = '';
if ($foto && $foto['error'] == 0) {
    $directorio = "uploads/fotos/";
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
    $nombreFoto = "foto_" . time() . "_" . rand(1000, 9999) . "." . $extension;
    $rutaCompleta = $directorio . $nombreFoto;

    if (!move_uploaded_file($foto['tmp_name'], $rutaCompleta)) {
        die("Error al guardar la foto.");
    }
}

// 3. Insertar en donacionesespecie
$stmt2 = $conexion->prepare("INSERT INTO donacionesespecie (idDonacion, descripcion, cantidad, estado, foto) VALUES (?, ?, ?, ?, ?)");
$stmt2->bind_param("isiss", $idDonacion, $descripcion, $cantidad, $estado, $nombreFoto);

if (!$stmt2->execute()) {
    die("Error al registrar detalles de especie: " . $stmt2->error);
}

echo "<script>alert('Donación registrada exitosamente.'); window.location.href='donacionesUsuario.php';</script>";
?>
