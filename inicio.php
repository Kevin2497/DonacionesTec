<!--
/*************************************************************/
/* Archivo:  index.php
 * Objetivo: página inicial de manejo de catálogo,
 *           incluye manejo de sesiones y plantillas
 * Autor:
 *************************************************************/ -->
<?php
include_once("vista/encabezado.html");
include_once("menu.php");
include_once("modelo/Usuario.php"); 


$conexion = new mysqli("localhost", "root", "", "donacionesTec", 3307);
    if ($conexion->connect_errno) {
        die("Error de conexión: " . $conexion->connect_error);
    }
$rol = "";
if (isset($_SESSION["usuario"])) {
    $usuario = unserialize($_SESSION["usuario"]);
    $nombre = $usuario->getNombre();
    $rol = $usuario->getRol(); // Asegúrate de que este método exista
}

// Obtener todas las donaciones del usuario
$stmt = $conexion->prepare("
    SELECT d.idDonacion, d.tipoDonacion, d.fecha, d.estado,
        dp.descripcion AS descripcion_propuesta,
        de.descripcion AS descripcion_especie,
        de.cantidad AS cantidad_especie
    FROM donaciones d
    LEFT JOIN donacionespropuesta dp ON d.idDonacion = dp.idDonacion
    LEFT JOIN donacionesespecie de ON d.idDonacion = de.idDonacion
    ORDER BY d.fecha DESC
");

$stmt->execute();
$resultado = $stmt->get_result();


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
        LIMIT 10;";
    $resTop = $conexion->query($sqlTopDonantes);
    $resTop2 = $conexion->query($sqlTopDonantes);

?>

<div style="display: flex;">

    <?php include_once("vista/aside.php"); ?>
    <head>
        <meta charset="UTF-8">
        <title>Inicio</title>
    </head>
    <section class="principal">
        <img class="imgPrincipal" src="img/indexprin2.jpg">
        <h1>Bienvenido 👋</h1>

        <?php if ($rol === "admin"): ?>
        <!-- Opciones para ADMIN -->
        <section>
            <h2>Panel de administración</h2>
            <br>

            <h2>Historial de donaciones</h2>
            <section class="historial">

                <?php if ($resultado->num_rows > 0): ?>
                <table class="tabla1">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($fila['fecha']) ?></td>
                                <td><?= htmlspecialchars($fila['tipoDonacion']) ?></td>
                                <td><?= htmlspecialchars($fila['estado']) ?></td>
                                <td>
                                    <?php 
                                        if ($fila['tipoDonacion'] === 'propuesta') {
                                            echo htmlspecialchars($fila['descripcion_propuesta']);
                                        } elseif ($fila['tipoDonacion'] === 'especie') {
                                            echo htmlspecialchars($fila['descripcion_especie']) . " (Cantidad: " . htmlspecialchars($fila['cantidad_especie']) . ")";
                                        } else {
                                            echo "Donación monetaria.";
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No has realizado donaciones todavía.</p>
            <?php endif; ?>

            </section>

            
            <section class="boxCalendario">
                <h2 class="h2Calendario">Calendario</h2>
                <section class="calendario">

                    <table id="calendar">
                    <thead>
                    <caption> </caption>
                    <tr>
                        <th>
                                    Lun
                        </th>
                        <th>
                                    Mar
                        </th>
                        <th>
                                    Mie
                        </th>
                        <th>
                                    Jue
                        </th>
                        <th>
                                    Vie
                        </th>
                        <th>
                                    Sab
                        </th>
                        <th>
                                    Dom
                        </th>
                    </tr>
                    </thead>
                <tbody> </tbody>
                    </table>

                </section>

            </section>

            <br>

        </section>
        <?php else: ?>
        <!-- Opciones para USUARIO -->
        <section class="principal">

        <!-- Hero principal -->

        <div class="hero">
            
            <h1>DonacionesTec</h1>
            <h2>Un espacio para unir voluntades, cambiar realidades.</h2>
            <p>Gracias a ti, llevamos ayuda donde más se necesita. Únete, participa, transforma.</p>
        </div>

        <!-- Nueva sección informativa -->
        <div class="info-donaciones">
            <h2>¿Por qué donar?</h2>
            <p>Tu apoyo permite mejorar instalaciones, brindar becas, adquirir equipo tecnológico y mucho más. En DonacionesTec creemos que cada aportación construye futuro.</p>

            <h2>Formas de colaborar</h2>
            <ul>
                <li>📦 Donaciones en especie (útiles, libros, equipo).</li>
                <li>💳 Aportaciones monetarias seguras.</li>
                <li>🤝 Participación en eventos solidarios.</li>
            </ul>
        </div>


            <!-- Sección Cómo Funciona -->
            <div class="como-funciona">
                <h2> ¿Cómo funciona?</h2>
                <ol>
                    <li>Explora los proyectos activos.</li>
                    <li>Elige uno que te inspire.</li>
                    <li>Dona en especie, monetariamente o con una propuesta propia.</li>
                </ol>
            </div>
            <br>
            <!-- Sección de maximos donantes -->
            <div class="maximosDonantes">
                <h1>Mejores donadores monetarios</h2>
                
                <div class="tabla-scroll">
                    <table  class="donantesIndex">
                        <tr>
                            <th>Nombre</th>
                            <th>Total monetario donado</th>
                        </tr>
                        <?php while ($row = $resTop->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row["nombre"] ?></td>
                            <td>$<?= number_format($row["total_monetario"], 2) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
                
            </div>

                    <!-- Sección de maximos donantes -->
            <div class="maximosDonantes">
                <h1>Mejores donadores en especie</h2>
                
                <div class="tabla-scroll">
                    <table  class="donantesIndex">
                        <tr>
                            <th>Nombre</th>
                            <th>Cantidad de productos donados</th>
                        </tr>
                        <?php while ($row = $resTop2->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row["nombre"] ?></td>
                            <td><?= number_format($row["total_especie"]) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
                
            </div>
        </section>


        <?php endif; ?>
    </section>

    <script src="controlador/calendar.js"></script>
</div>


<?php
include_once("vista/pie.html");
?>

