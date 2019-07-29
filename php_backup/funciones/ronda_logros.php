<?php 
	ini_set('max_execution_time', 3000); 
	require_once("../../conexion.php");

	$fecha_for_1 = date("Y-m-d");
	$fecha_for_2 = date("H:i:s");
	$fecha_comp = $fecha_for_1." ".$fecha_for_2;

	$act_part = $_GET["actual_part"];
	$id_liga_get = $_GET["IDE"];
	$codigo = $_GET["generate"];
	$codigo2 = $_GET["codigo2"];

	$nuevo_upd = $act_part + 1;	

	$con_tipo1 = "SELECT * FROM liga WHERE id_liga = '$id_liga_get'";
	$ex_con_tipo1 = $conexion->query($con_tipo1);
	$reg_con_tipo1 = $ex_con_tipo1->fetch_assoc();

	$id_deporte = $reg_con_tipo1["id_categoria"];

	$con_cat1 = "SELECT * FROM categoria WHERE id_categoria = '$id_deporte'";
	$ex_con_cat1 = $conexion->query($con_cat1);
	$reg_con_cat1 = $ex_con_cat1->fetch_assoc();

	$name_deporte = $reg_con_cat1["descripcion"];	

	if ($act_part == "0") {
		$con_market1 = "SELECT * FROM p_futbol WHERE id_liga = '$id_liga_get' AND fecha_inicio >= '$fecha_comp' ORDER BY id_partido ASC";
		$ex_con_market1 = $conexion->query($con_market1);
		$regs_con_market1 = $ex_con_market1->num_rows;

		$total_parts = $regs_con_market1;
		$codigo = substr(md5(rand()),0,10);

		$upd_con_parti1 = "UPDATE p_futbol SET guia = '$codigo' WHERE id_liga = '$id_liga_get' AND fecha_inicio >= '$fecha_comp'";
		if ($ej_upd_parti1 = $conexion->query($upd_con_parti1)) {
		    $con_market1 = "SELECT * FROM p_futbol WHERE id_liga = '$id_liga_get' AND fecha_inicio >= '$fecha_comp' AND guia = '$codigo' ORDER BY id_partido ASC";
			$ex_con_market1 = $conexion->query($con_market1);
			$regs_con_market1 = $ex_con_market1->num_rows;
		}		
	} else {
		$con_market1 = "SELECT * FROM p_futbol WHERE id_liga = '$id_liga_get' AND fecha_inicio >= '$fecha_comp' AND guia = '$codigo' ORDER BY id_partido ASC";
		$ex_con_market1 = $conexion->query($con_market1);
		$regs_con_market1 = $ex_con_market1->num_rows;
	}	

	if ($regs_con_market1 > 0) {
		$prt_partidos = $ex_con_market1->fetch_assoc();
			
		$id_partido = $prt_partidos["id_partido"];

		if ($codigo2 == "") {
			$guia2 = substr(md5(rand()),0,10);
			$codigo2 = $guia2;
			$upd_con_parti2 = "UPDATE url_partido SET guia = '$guia2' WHERE id_partido = '$id_partido'";
			if ($ej_upd_parti2 = $conexion->query($upd_con_parti2)) {

			}
		}

		$con_url = "SELECT * FROM url_partido WHERE id_partido = '$id_partido' AND guia = '$codigo2'";
		$ex_con_url = $conexion->query($con_url);
		$num_con_url = $ex_con_url->num_rows;

		if ($num_con_url > 0) {
			$res_url = $ex_con_url->fetch_assoc();	
			$url_id = $res_url["id_url_partido"];
			$url_inicial = $res_url["url"];

			$url_inicial = str_replace("Ã¡", "á", $url_inicial);
		$url_inicial = str_replace("Ã©", "é", $url_inicial);
		$url_inicial = str_replace("Ã­", "í", $url_inicial);
		$url_inicial = str_replace("Ã³", "ó", $url_inicial);
		$url_inicial = str_replace("Ãº", "ú", $url_inicial);

		$url_inicial = str_replace("Ã±", "ñ", $url_inicial);	
		$url_inicial = str_replace("Ã§", "ç", $url_inicial);

		$url_inicial = str_replace("Ã", "Á", $url_inicial);
		$url_inicial = str_replace("Ã‰", "É", $url_inicial);
		$url_inicial = str_replace("Ã", "Í", $url_inicial);
		$url_inicial = str_replace("Ã“", "Ó", $url_inicial);
		$url_inicial = str_replace("Ãš", "Ú", $url_inicial);

		$url_inicial = str_replace("Ã‘", "Ñ", $url_inicial);	
		$url_inicial = str_replace("Ã‡", "Ç", $url_inicial);	

		$url_inicial = str_replace("Ã¤", "ä", $url_inicial);
		$url_inicial = str_replace("Á«", "ë", $url_inicial);
		$url_inicial = str_replace("Ã¯", "ï", $url_inicial);
		$url_inicial = str_replace("Á¶", "ö", $url_inicial);
		$url_inicial = str_replace("Á¼", "ü", $url_inicial);

		$url_inicial = str_replace("Ã„", "Ä", $url_inicial);
		$url_inicial = str_replace("Ã‹", "Ë", $url_inicial);
		$url_inicial = str_replace("Ã", "Í", $url_inicial);
		$url_inicial = str_replace("Ã–", "Ö", $url_inicial);
		$url_inicial = str_replace("Ãœ", "Ü", $url_inicial);

		$url_inicial = str_replace("Á¸", "ø", $url_inicial);
		$url_inicial = str_replace("Áª", "ê", $url_inicial);
		$url_inicial = str_replace("Á£", "ã", $url_inicial);
		$url_inicial = str_replace("Á¹", "ù", $url_inicial);
		$url_inicial = str_replace("Á¨", "è", $url_inicial);		

			$url_partido = "http://odds.football-data.co.uk".$url_inicial;
                        
            echo "$url_partido<br>";

			$html = file_get_contents(utf8_decode("$url_partido"."/all-odds"));

			$pokemon_doc = new DOMDocument();

			libxml_use_internal_errors(TRUE);

			if(!empty($html)){
				$pokemon_doc->loadHTML($html);
				libxml_clear_errors();

				$pokemon_xpath = new DOMXPath($pokemon_doc);

				$title_h1 = $pokemon_xpath->query('//h1');

				if($title_h1->length > 0){
					foreach($title_h1 as $titular){
						$nombre_enc = utf8_decode(addslashes($titular->nodeValue));
						$nombre_enc = substr($nombre_enc, 1);

						echo "$nombre_enc<br>";

						$juego_linea = explode(": ", $nombre_enc);

						$equipos = explode(" v ", $juego_linea[0]);

						$equipo1 = trim($equipos[0]);
						$equipo2 = trim($equipos[1]);

						$tipo_ap_1 = explode("	", $juego_linea[1]);

						$nombre_tipo = preg_replace('/\s\s+/', ' ', $tipo_ap_1[0]);	
						$apuesta_name = trim($nombre_tipo);

						$tipo_apuesta_name = $apuesta_name." $name_deporte";
						$tipo_apuesta_name = $tipo_apuesta_name;

						$con_ta1 = "SELECT * FROM tipo_apuesta WHERE descripcion_wihi_ta = '$tipo_apuesta_name'";
						$ex_con_ta1 = $conexion->query($con_ta1);
						$regs_con_ta1 = $ex_con_ta1->num_rows;

						if ($regs_con_ta1 != '1') {
							$con_ta2 = "INSERT INTO tipo_apuesta (id_categoria, descripcion_wihi_ta) VALUES ('$id_deporte','$tipo_apuesta_name')";
							$ex_con_ta1 = $conexion->query($con_ta2);
							if($ex_con_ta1){ $id_ta = $conexion->insert_id; } else { printf("Errormessage: %s\n", $conexion->error); }
						} else {
							$bus_con_ta1 = $ex_con_ta1->fetch_assoc();				            
			        		$id_ta = $bus_con_ta1["id_ta"];
						}	
					}
				}

				echo "**$apuesta_name**<br>";

				switch ($apuesta_name) {
					case 'Match Result':
						$contenido = "procesamiento/win_choice.php";
					break;  

					case 'Money Line':
						$contenido = "procesamiento/win_choice.php";
					break;  	

					case 'Fight Result':
						$contenido = "procesamiento/win_choice.php";
					break;

					case 'Over/Under':
						$contenido = "procesamiento/underover.php";
					break;

					case 'Asian Handicap':
						$contenido = "procesamiento/dif_asiatica.php";
					break;
				}

				include($contenido);						
			}
		} else {
			$upd_con_parti3 = "UPDATE p_futbol SET guia = '' WHERE id_partido = '$id_partido'";
			echo "$upd_con_parti3<br>";
			if ($ej_upd_parti3 = $conexion->query($upd_con_parti3)) {
				echo "aja";
			    echo "<script type='text/javascript'>window.parent.$('#3').load('ronda_logros.php?IDE=$id_liga_get&actual_part=$nuevo_upd&codigo2=&generate=$codigo');</script>";
			}
		}
	} else {	
		echo "<script type='text/javascript'>
				
				window.parent.$('#2').html('Listo');
				window.parent.$('#3').html('Listo');
				
			  </script>";
	}	
	
?>