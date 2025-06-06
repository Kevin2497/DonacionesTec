<?php
function conexionBDD(){
    $conexion = new mysqli("localhost", "root", "", "donacionesTec", 3307);
    if ($conexion->connect_errno) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    return $conexion;
}

function obtenerDatosDonacionMonetaria($idDonacion) {
    $conexion = conexionBDD();
    $stmt = $conexion->prepare("SELECT dm.monto, dm.metodoPago, u.nombre 
        FROM donacionesmonetarias dm 
        JOIN donaciones d ON dm.idDonacion = d.idDonacion 
        JOIN usuarios u ON d.idUsuario = u.idUsuario 
        WHERE dm.idDonacion = ?");
    $stmt->bind_param("i", $idDonacion);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
        die("No se encontró la donación.");
    }

    $stmt->close();
    $conexion->close();

    return $row;
}

function actualizarNombreComprobante($idDonacion, $nombreArchivo) {
    $conexion = conexionBDD();
    $stmt = $conexion->prepare("UPDATE donacionesmonetarias SET comprobante = ? WHERE idDonacion = ?");
    $stmt->bind_param("si", $nombreArchivo, $idDonacion);
    $stmt->execute();
    
    $stmt->close();
    $conexion->close();
}


function obtenerDetalleEspecieF($id){
    $conexion = conexionBDD();
    $stmt = $conexion->prepare("SELECT descripcion, cantidad, estado, foto, comprobante FROM donacionesespecie WHERE idDonacion = ?");
    if (!$stmt) {
        die("Error en prepare: " . $conexion->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conexion->close();

    return $result;

}

function obtenerDetalleMonetariaF($idDonacion){
    $conexion = conexionBDD();
    $stmt = $conexion->prepare("SELECT monto, metodoPago, comprobante FROM donacionesmonetarias WHERE idDonacion = ?");
    $stmt->bind_param("i", $idDonacion);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $stmt->close();
    $conexion->close();

    return $resultado;
}

function obtenerDetallePropuestaF($id){
    $conexion = conexionBDD();
    $stmt = $conexion->prepare("SELECT dp.descripcion, dp.archivo, d.estado 
    FROM donacionespropuesta dp
    JOIN donaciones d ON dp.idDonacion = d.idDonacion
    WHERE dp.idDonacion = ?");
    if (!$stmt) {
        die("Error en prepare: " . $conexion->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conexion->close();

    return $result;
}

function validarCorreo(){
    $conexion=conexionBDD();
    
    $verificar = $conexion->prepare("SELECT idUsuario FROM usuarios WHERE correo = ?");
    $verificar->bind_param("s", $correo);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        die("Ya existe una cuenta con este correo.");
    }
}

function insertarNuevoUsuario($nombre, $correo, $telefono, $direccion, $fechaNac, $password){
    $conexion=conexionBDD();
    
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, telefono, direccion, fechaNac, contrasena, rol) VALUES (?, ?, ?, ?, ?, ?, 'usuario')");
    $stmt->bind_param("ssssss", $nombre, $correo, $telefono, $direccion, $fechaNac, $password);

    if ($stmt->execute()) {
        header("Location: ../logeo.php");
        exit;
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}

function obtenerDatosDonacionParaFicha($conexion, $idDonacion) {
    $sql = "SELECT u.nombre, d.tipoDonacion, d.referencia, p.nombre AS nombreProyecto
            FROM donaciones d
            JOIN usuarios u ON d.idUsuario = u.idUsuario
            LEFT JOIN proyectos p ON d.idProyecto = p.idProyecto
            WHERE d.idDonacion = ?";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param("i", $idDonacion);
    $stmt->execute();
    $stmt->get_result()->fetch_assoc();
    $conexion->close();
    return $stmt;
}

?>
