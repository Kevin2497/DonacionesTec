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
$idUsuario = $oUsu->getIdUsuario();

$conexion = new mysqli("localhost", "root", "", "donacionesTec", 3307);
if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener los datos del usuario logueado
$sql = "SELECT idUsuario, nombre, correo, contrasena, telefono, direccion, fechaNac, fotoPerfil, rol FROM usuarios 
        WHERE idUsuario = $idUsuario";

$resultado = $conexion->query($sql);    

?>

<div style="display: flex;">
    <?php include_once("vista/aside.php"); ?>
    
    <section class="principal">

        
        
        <br>
        <?php if ($resultado && $resultado->num_rows > 0): 
            $fila = $resultado->fetch_assoc();
        ?>
        <button onclick="abrirPopup()" class="boton-modificar">Modificar Información</button>
        <!-- Modal emergente oculto por defecto -->
        <div id="popup" class="modal">
            <div class="modal-contenido">
                <span class="cerrar" onclick="cerrarPopup()">&times;</span>
                <h2>Modificar tu perfil</h2>
                <form method="POST" action="actualizarPerfil.php" onsubmit="return enviarFormulario(this)">
                    <label>Nombre:</label><br>
                    <input type="text" name="nombre" value="<?php echo $fila["nombre"]; ?>"><br><br>

                    <label>Correo:</label><br>
                    <input type="email" name="correo" value="<?php echo $fila["correo"]; ?>"><br><br>

                    <label>Teléfono:</label><br>
                    <input type="text" name="telefono" value="<?php echo $fila["telefono"]; ?>"><br><br>

                    <label>Dirección:</label><br>
                    <input type="text" name="direccion" value="<?php echo $fila["direccion"]; ?>"><br><br>

                    <button type="submit">Guardar Cambios</button>
                </form>
            </div>
        </div>
            <div>
                <br>
                <h1>Tu perfil</h1>
                
                <h2>Foto de Perfil:</h2>
                <img src="<?php echo $fila["fotoPerfil"]; ?>" alt="Foto de Perfil" width="150"><br><br>
                <h2>ID Usuario: <?php echo $fila["idUsuario"]; ?></h2>
                <h2>Nombre: <?php echo $fila["nombre"]; ?></h2>
                <h2>Correo: <?php echo $fila["correo"]; ?></h2>
                <h2>Teléfono: <?php echo $fila["telefono"]; ?></h2>
                <h2>Dirección: <?php echo $fila["direccion"]; ?></h2>
                <h2>Fecha de Nacimiento: <?php echo $fila["fechaNac"]; ?></h2>
                <h2>Rol: <?php echo $fila["rol"]; ?></h2>  
            </div>
        <?php else: ?>
            <p style="text-align: center;">No estás registrado</p>
        <?php endif; ?>

       
    </section>

    <script src="controlador/popup.js"></script>
</div>

<?php
include_once("vista/pie.html");
?>

