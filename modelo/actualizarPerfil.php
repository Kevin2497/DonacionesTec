<?php
session_start();
include_once("modelo/Usuario.php");

// Validar sesión
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

// Obtener datos del formulario
$nombre = $_POST['nombre'] ?? null;
$correo = $_POST['correo'] ?? null;
$telefono = $_POST['telefono'] ?? null;
$direccion = $_POST['direccion'] ?? null;

$oUsu = unserialize($_SESSION["usuario"]);
$idUsuario = $oUsu->getIdUsuario();

// Verificar que no vengan vacíos (opcional)
if (!$nombre || !$correo || !$telefono || !$direccion) {
    echo "Faltan datos en el formulario.";
    exit;
}

// Conexión
$conexion = new mysqli("localhost", "root", "", "donacionesTec", 3307);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Actualizar en la BD
$sql = "UPDATE usuarios SET nombre = ?, correo = ?, telefono = ?, direccion = ? WHERE idUsuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssi", $nombre, $correo, $telefono, $direccion, $idUsuario);

if ($stmt->execute()) {
    // Actualiza objeto en sesión también
    $oUsu->setNombre($nombre);
    $oUsu->setCorreo($correo);
    $oUsu->setTelefono($telefono);
    $oUsu->setDireccion($direccion);
    $_SESSION["usuario"] = serialize($oUsu);

    header("Location: miPerfil.php");
    exit;
} else {
    echo "Error al actualizar: " . $stmt->error;
}
?>
