<?php
require_once("../modelo/consultasDonaciones.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $fechaNac = $_POST['fechaNac'];
    $password = $_POST['password'];
    $confirmar = $_POST['confirmar'];
    

    if ($password !== $confirmar) {
        die("Las contraseñas no coinciden.");
    }

    $conexion = conexionBDD();

    validarCorreo($conexion);
    
    

    insertarNuevoUsuario($nombre, $correo, $telefono, $direccion, $fechaNac, $password);

}
?>
