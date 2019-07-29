<?php 
	ini_set('max_execution_time', 3000); 
	require_once("../../conexion.php");

	//Cargo fecha actual del sistema
	$fecha_for_1 = date("Y-m-d");
	$fecha_for_2 = date("H:i:s");
	$fecha_comp = $fecha_for_1." ".$fecha_for_2;

	$act_part = $_GET["actual_part"];
	$id_liga_get = $_GET["IDE"];


    if (isset($_GET["generate"])) {
    	$codigo = $_GET["generate"];
    }	

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
		$guia = substr(md5(rand()),0,10);
		$codigo = $guia;
		$upd_con_parti1 = "UPDATE p_futbol SET guia = '$guia' WHERE id_liga = '$id_liga_get' AND fecha_inicio >= '$fecha_comp'";
		if ($ej_upd_parti1 = $conexion->query($upd_con_parti1)) {}
	}

	$con_market1 = "SELECT * FROM p_futbol WHERE id_liga = '$id_liga_get' AND fecha_inicio >= '$fecha_comp' AND guia = '$codigo' ORDER BY id_partido ASC";
	$ex_con_market1 = $conexion->query($con_market1);
	$regs_con_market1 = $ex_con_market1->num_rows;

	echo "$regs_con_market1<br>";

	if ($regs_con_market1 > 0) {

		$prt_partidos = $ex_con_market1->fetch_assoc();
			
		$id_partido = $prt_partidos["id_partido"];
		$url_inicial = $prt_partidos["url"];

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

		$html = file_get_contents(utf8_decode($url_partido));

		$pokemon_doc = new DOMDocument();

		libxml_use_internal_errors(TRUE);

		if(!empty($html)){

			$pokemon_doc->loadHTML($html);
			libxml_clear_errors();

			$pokemon_xpath = new DOMXPath($pokemon_doc);			

			$cada_tipo = $pokemon_xpath->query('//div[@class="subContainer"] //table[@class="listOne"] //td[@class="event"]');

			if($cada_tipo->length > 0){
				foreach($cada_tipo as $row){

					$nombre_tipo = ""; $url_partido=""; $url_tipo = "";					

					$nombre_tipo = utf8_decode(addslashes($row->nodeValue));
					$nombre_tipo = preg_replace('/\s\s+/', ' ', $nombre_tipo);	
					$nombre_tipo = trim($nombre_tipo);

					$links=$row->getElementsByTagName('a');
					$i = 0;					

					foreach($links as $a) {
				        $url_partido = addslashes($a->getAttribute('href'));

				        if ($i == 0) {
				        	$url_tipo = $url_partido;
				        }
				        $i++;
				    }	

				    if ($url_tipo != "" AND ($nombre_tipo == "Money Line" /*OR $nombre_tipo == "Point Spread" OR $nombre_tipo == "Total Points"*/ OR $nombre_tipo == "Match Result" /* OR $nombre_tipo == "Asian Handicap" OR $nombre_tipo == "Over/Under"*/) AND $nombre_tipo != "") {

				    	echo "Tipo Apuesta: $nombre_tipo<br>URL: $url_tipo<br><br>";		

				    	if ($id_deporte == '23' AND $nombre_tipo == 'Match Result') {
    			    		echo "<br> No se agregó <br>";
    			    	} else {

					    	$tipo_apuesta_name = $nombre_tipo." $name_deporte";
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

							$con_ta3 = "SELECT * FROM url_partido WHERE id_partido = '$id_partido' AND id_ta = '$id_ta' AND url = '$url_tipo'";
							$ex_con_ta3 = $conexion->query($con_ta3);
							$regs_con_ta3 = $ex_con_ta3->num_rows;

							if ($regs_con_ta3 == '0') {
								$con_ta4 = "INSERT INTO url_partido (id_partido, id_ta, url) VALUES ('$id_partido','$id_ta','$url_tipo')";
								$ex_con_ta4 = $conexion->query($con_ta4);
								if($ex_con_ta4){ $id_url_partido = $conexion->insert_id; } else { printf("Errormessage: %s\n", $conexion->error); }
							} else {
								$bus_con_ta3 = $ex_con_ta3->fetch_assoc();				            
				        		$id_url_partido = $bus_con_ta3["id_url_partido"];
							}
						}
				    }																									
				}
			}
		}

		$upd_con_parti1 = "UPDATE p_futbol SET guia = '' WHERE id_partido = '$id_partido'";
		if ($ej_upd_parti1 = $conexion->query($upd_con_parti1)) {

		    echo "<script type='text/javascript'>window.parent.$('#1').load('tipos_por_partido.php?IDE=$id_liga_get&total_parts=$total_parts&actual_part=$nuevo_upd&generate=$codigo');</script>";
	
		}
	} else {		
		echo "Enviado a Zona 2<br><br>";
		echo "<script type='text/javascript'>window.parent.$('#2').load('ronda_logros.php?IDE=$id_liga_get&total_parts=0&actual_part=0');</script>";
	}
?>