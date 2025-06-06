<?php 
include_once("AccesoDatos.php");

class Usuario {
	private $idUsuario = 0;
	private $correo = "";
	private $contrasena = "";
	private $nombre = "";
	private $rol = ""; // 'admin' o 'usuario'
	private $oAD = null;

	public function setCorreo($valor) { $this->correo = $valor; }
	public function setContrasena($valor) { $this->contrasena = $valor; }

	public function getNombre() { return $this->nombre; }
	public function getRol() { return $this->rol; }
	public function getIdUsuario() { return $this->idUsuario; }

	public function buscarCorreoYContrasena() {
		if (empty($this->correo) || empty($this->contrasena)) {
			throw new Exception("Faltan datos.");
		}
		
		$oAD = new AccesoDatos();
		if ($oAD->conectar()) {
			
			$sQuery = "SELECT idUsuario, nombre, rol FROM usuarios 
			           WHERE correo = '$this->correo' AND contrasena = '$this->contrasena'";
			$arrRS = $oAD->ejecutarConsulta($sQuery);
			$oAD->desconectar();

			if ($arrRS != null) {
				$this->idUsuario = $arrRS[0][0];
				$this->nombre = $arrRS[0][1];
				$this->rol = $arrRS[0][2];
				return true;
			}
		}
		return false;
	}
}
?>
