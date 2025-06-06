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

// Requiere conexión activa: $conexion
$conexion = new mysqli("localhost", "root", "", "donacionesTec", 3307);
if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

// 1. Top Donantes
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

// 2. Últimos usuarios
$sqlUltimos = "SELECT nombre, correo, idUsuario 
               FROM usuarios 
               ORDER BY idUsuario DESC 
               LIMIT 10";
$resUltimos = $conexion->query($sqlUltimos);

$sqlUsuariosPorMes = "SELECT 
    DATE_FORMAT(fechaRegistro, '%Y-%m') AS mes,
    COUNT(*) AS total
    FROM usuarios
    GROUP BY mes
    ORDER BY mes";

$resUsuarios = $conexion->query($sqlUsuariosPorMes);

$meses = [];
$totales = [];

while ($row = $resUsuarios->fetch_assoc()) {
    $meses[] = $row['mes'];
    $totales[] = $row['total'];
}

?>
<div style="display: flex;">

    <?php include_once("vista/aside.php"); ?>
    
    <head>
        <meta charset="UTF-8">
        <title>Estadisticas</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <section class="principal">
        <h2>🏆 Máximos Donantes</h2>
        <div class="tabla-scroll">
            <table border="1" cellpadding="5" class="donantes">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Monetario</th>
                    <th>Productos</th>
                    <th>Proyectos</th>
                    <th>Total</th>
                </tr>
                <?php while ($row = $resTop->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["nombre"] ?></td>
                    <td><?= $row["correo"] ?></td>
                    <td>$<?= $row["total_monetario"] ?></td>
                    <td><?= $row["total_especie"] ?></td>
                    <td><?= $row["total_proyectos"] ?></td>
                    <td><strong><?= $row["total_general"] ?></strong></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <br><br>

        <h2>🆕 Últimos Usuarios Registrados</h2>
        <div class="tabla-scroll">
            <table border="1" cellpadding="5" class="donantes">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                </tr>
                <?php while ($row = $resUltimos->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["nombre"] ?></td>
                    <td><?= $row["correo"] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <h2>📈 Crecimiento de Usuarios Registrados</h2>
        <canvas id="graficaStats"></canvas>
        <script>
            // Variables que se usarán en el JS externo
            window.mesesUsuarios = <?= json_encode($meses) ?>;
            window.totalesUsuarios = <?= json_encode($totales) ?>;
    </script>
        

    </section>
    <script src="controlador/graficaStats.js"></script>
    
</div>

<?php
include_once("vista/pie.html");
?>

