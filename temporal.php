<?php
include_once("vista/encabezado.html");
include_once("menu.php");
include_once("modelo/Usuario.php"); 

session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

$oUsu = unserialize($_SESSION["usuario"]);
$rol = $oUsu->getRol();

if ($rol !== "admin") {
    echo "<h2>No tienes permisos para acceder a esta página.</h2>";
    exit;
}

$conexion = new mysqli("localhost", "root", "", "donacionesTec", 3307);
if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener usuarios de tipo 'usuario'
$sql = "SELECT idUsuario, nombre, correo, telefono, direccion, fechaNac, fotoPerfil, rol, idDonacion FROM usuarios WHERE rol = 'usuario'";
$resultado = $conexion->query($sql);
?>



<div style="display: flex;">

    <?php include_once("vista/aside.php"); ?>
    <head>
        <meta charset="UTF-8">
        <title>Inicio</title>
    </head>
    <section class="principal">
        <h1>Donantes</h1>

        <?php if ($resultado->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Telefono</th>
                        <th>Direccion</th>
                        <th>Fecha Nac</th>
                        <th>Foto</th>
                        <th>Rol</th>
                        <th>IdDonacion</th>    
                        <th>Accion</th>  
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['idUsuario']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['correo']) ?></td>
                            <td><?= htmlspecialchars($fila['telefono']) ?></td>
                            <td><?= htmlspecialchars($fila['direccion']) ?></td>
                            <td><?= htmlspecialchars($fila['fechaNac']) ?></td>
                            <td><?= htmlspecialchars($fila['fotoPerfil']) ?></td>
                            <td><?= htmlspecialchars($fila['rol']) ?></td>
                            <td><?= htmlspecialchars($fila['idDonacion']) ?></td>
                            <td>
                                <a href="actualizar.php?id=<?php echo $fila["idUsuario"]; ?>">Ver Perfil</a>
                            </td>
                        </tr>


                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No hay usuarios registrados con rol 'usuario'.</p>
        <?php endif; ?>

    </section>

</div>

<?php
include_once("vista/pie.html");
?>
