<?php 

	require_once("../../../conexion.php");	

	$nombre_liga = $_POST["name_tit_1"];
	$url_liga = $_POST["url_tit_1"];
	$url_liga = $conexion->real_escape_string($url_liga);
	$nacionalidad = $_POST["nacionali_lig"];
	$categoria = $_POST["categori_lig"];

	$con_tipo1 = "SELECT * FROM liga WHERE url = '$url_liga'";
	$ex_con_tipo1 = $conexion->query($con_tipo1);
	$regs_con_tipo1 = $ex_con_tipo1->num_rows;

	if ($regs_con_tipo1 == '0') {
		$con_tipo2 = "INSERT INTO liga (id_wihi_liga, nombre_liga, id_categoria, id_pais, url) VALUES ('Por confirmar','$nombre_liga','$categoria','$nacionalidad','$url_liga')";

		if($ex_con_tipo2 = $conexion->query($con_tipo2)){ 
			$id_liga = $conexion->insert_id;
			echo "Liga creada bajo el ID: $id_liga<br>";
		} 
	} else {
		echo "Liga ya existente. Revise las ligas ya creadas.<br>";

		$bus_con_market1 = $ex_con_tipo1->fetch_assoc();

		echo $bus_con_market1['nombre_liga'];
	}

?>