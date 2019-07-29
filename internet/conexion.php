<?php
	date_default_timezone_set('America/Toronto');
	function conectarse() {		
		$servidor = "localhost"; $usuario = "id6813098_betzone"; $password = "gabo19071991"; $bd = "id6813098_betzone";
		$conectar = new mysqli($servidor, $usuario, $password, $bd);
		return $conectar; }
	$conexion = conectarse();
?>