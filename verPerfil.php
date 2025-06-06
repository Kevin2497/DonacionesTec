<?php
if (!isset($_GET['id'])) {
    echo "<p>Usuario no especificado.</p>";
    exit;
}

$conexion = new mysqli("localhost", "root", "", "donacionesTec", 3307);
if ($conexion->connect_errno) {
    die("<p>Error de conexión: " . $conexion->connect_error . "</p>");
}

$id = $_GET['id'];

$stmt = $conexion->prepare("SELECT nombre, correo, telefono, rol FROM usuarios WHERE idUsuario = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($usuario = $resultado->fetch_assoc()) {
    echo '<div style="padding: 20px;">';
    echo "<p><strong>Nombre:</strong><br>" . htmlspecialchars($usuario['nombre']) . "</p>";
    echo "<p><strong>Correo:</strong><br>" . htmlspecialchars($usuario['correo']) . "</p>";
    echo "<p><strong>Teléfono:</strong><br>" . htmlspecialchars($usuario['telefono']) . "</p>";
    echo "<p><strong>Rol:</strong><br>" . htmlspecialchars($usuario['rol']) . "</p>";
    echo '</div>';
} else {
    echo "<p>Usuario no encontrado.</p>";
}
?>
