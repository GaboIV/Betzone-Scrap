<?php
	date_default_timezone_set('America/Toronto');
	function conectarse() {		
		$servidor = "localhost"; $usuario = "root"; $password = ""; $bd = "betzone";
		$conectar = new mysqli($servidor, $usuario, $password, $bd);
		return $conectar; }
	$conexion = conectarse();
?>