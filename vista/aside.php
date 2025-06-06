<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rol = "";
if (isset($_SESSION["usuario"])) {
    $usuario = unserialize($_SESSION["usuario"]);
    $rol = $usuario->getRol();
}  
?>

<aside class="barra-lateral">
    <nav>
        <ul>
            <li><a href="inicio.php">&#x1F3E0; Inicio</a></li>
            <li><a href="index.php">&#x1F64C; ¿Por qué donar?</a></li>
            <li><a  href="index.php">&#x1F4E0; Contacto</a></li>

            <br>
            <?php if ($rol === 'admin'): ?>
                                <!-- Desplegable Admin -->
                <li class="submenu">
                    <input type="checkbox" id="usuarios-toggle" hidden>
                    <label for="usuarios-toggle">&#x1F465;▼ Gestion</label>
                    <ul class="submenu-items">
                        <li><a href="donantes.php">Donantes</a></li>
                        <li><a href="donaciones.php">Donaciones</a></li>
                        <li><a href="donaciones.php#donacionesEspecie">Proyectos</a></li>
                        <li><a href="donaciones.php#donacionesProductos">Productos</a></li>
                    </ul>
                </li>
                <br>
                <!-- Desplegable Admin -->
                <li class="submenu">
                    <input type="checkbox" id="donaciones-toggle" hidden>
                    <label for="donaciones-toggle">&#x1F4B0;▼ Reportes</label>
                    <ul class="submenu-items">
                        <li><a href="estadisticas.php">Estadisticas</a></li>
                        <li><a href="modelo/generarPDF.php" id="generarPDF">Generar PDF</a></li>
                    </ul>
                </li>
                <br>
                <!-- Desplegable Reportes -->
                <li class="submenu">
                    <input type="checkbox" id="reportes-toggle" hidden>
                    <label for="reportes-toggle">&#x1F4CA;▼ Configuracion</label>
                    <ul class="submenu-items">
                        <li><a href="miPerfil.php">Mi perfil</a></li>
                        <li><a href="logeo.php">Cerrar sesion</a></li>
                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($rol === 'usuario'): ?>

                <!-- Desplegable Gestion -->
                <li class="submenu">
                    <input type="checkbox" id="usuarios-toggle" hidden>
                    <label for="usuarios-toggle">&#x1F465;▼ Gestion</label>
                    <ul class="submenu-items">
                        <li><a href="donacionesUsuario.php">+ Nueva Donacion</a></li>
                        <li><a href="historialDonaciones.php">Historial de donaciones</a></li>
                        
                    </ul>
                </li>
                <br>
                <!-- Desplegable Reportes -->
                <li class="submenu">
                    <input type="checkbox" id="reportes-toggle" hidden>
                    <label for="reportes-toggle">&#x1F4CA;▼ Configuracion</label>
                    <ul class="submenu-items">
                        <li><a href="miPerfil.php">Mi perfil</a></li>
                        <li><a href="logeo.php">Cerrar sesion</a></li>
                    </ul>
                </li>
            <?php endif; ?>


        </ul>
    </nav>
</aside>
