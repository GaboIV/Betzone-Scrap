<?php 
	ini_set('max_execution_time', 3000); 
	require_once("../../conexion.php");

	$id_liga = $_GET["IDE"];

	$consulta = "SELECT * FROM liga WHERE id_liga = '$id_liga'";  
	$ejecutar_paginacion = $conexion->query($consulta);	 

	$regisdivo = $ejecutar_paginacion->fetch_assoc();

	$id_wihi_liga_ori = $regisdivo["id_wihi_liga"];

	$url_liga = $regisdivo["url"];

	if ($url_liga == "") {
		echo "No tiene asociada una URL la liga, asóciela y vuelva a intentarlo.";
	} else {
		$url_modificada = explode("http://odds.football-data.co.uk", $url_liga);

		$fraccion_url_mod = explode("/", $url_modificada[1]);

		$name_deporte = $fraccion_url_mod[1];	

		echo "Deporte: $name_deporte<br>";

		switch ($name_deporte) {

		    case "football":
		        $id_categoria = "21";
		        break;
		    case "baseball":
		        $id_categoria = "22";
		        break;		
		    case "basketball":
		        $id_categoria = "23";
		        break;
		    case "tennis":
		        $id_categoria = "24";
		        break;
		    case "american-football":
		        $id_categoria = "25";
		        break;
		}	

		echo "ID Deporte: $id_categoria<br><br>";
	}		

	$html = file_get_contents($url_liga);

	$pokemon_doc = new DOMDocument();

	libxml_use_internal_errors(TRUE);

	if(!empty($html)){

		$pokemon_doc->loadHTML($html);
		libxml_clear_errors();

		$pokemon_xpath = new DOMXPath($pokemon_doc);

		$titulo_liga = $pokemon_xpath->query('//div[@id="breadCrumbContainer"] //a');

		if($titulo_liga->length > 0){
			foreach($titulo_liga as $titleee){				

				$url_partido_2 = addslashes($titleee->getAttribute('href'));

				$url_ajust = str_replace("%C3%BA", "Ãº", $url_modificada[1]);

				if ($url_partido_2 == $url_ajust) {
					$liga_nombre = utf8_decode(addslashes($titleee->nodeValue));
					echo "Liga: $liga_nombre<br><br>";
				}
			}
		}

		if ($id_wihi_liga_ori == "Por confirmar") {
			$consulta = "UPDATE liga SET id_wihi_liga='$liga_nombre' WHERE id_liga='$id_liga'";

			if ($ejecutar_consulta = $conexion->query($consulta)) {
				echo "ID_Wihi cambiado<br><br>";
			}
		}	

		$title_h1 = $pokemon_xpath->query('//h1');

		if($title_h1->length > 0){
			foreach($title_h1 as $titular){
				$nombre_enc = utf8_decode(addslashes($titular->nodeValue));	

				echo "$nombre_enc<br>";	

				$liga_name = preg_replace('/\s\s+/', ' ', $nombre_enc);

				$div_league = explode(": ", $liga_name);

				$name_deporte_opt = trim($div_league[0]);	

				$name_deporte_uno = str_replace("-", " ", $name_deporte);			

				$compra = strnatcasecmp($name_deporte_uno, $name_deporte_opt);

				if ($compra == "0") {
					$pokemon_row = $pokemon_xpath->query('//table[@class="couponTable"] //tr');

					if($pokemon_row->length > 0){
						foreach($pokemon_row as $row){
							$frase = utf8_decode(addslashes($row->nodeValue));
							$frase = preg_replace('/\s\s+/', ' ', $frase);

							$links= $row->getElementsByTagName('a');

							$i = 0;

							foreach($links as $a) {
						        $url_partido = utf8_decode(addslashes($a->getAttribute('href')));

						        if ($i == 0) {
						        	$url_p_futbol = $url_partido;
						        	$foot_link = "http://odds.football-data.co.uk".$url_p_futbol;
						        }
						        $i++;
						    }			    

							if ($frase != " ") {

								if (preg_match("/Tuesday/", $frase) OR preg_match("/Wednesday/", $frase) OR preg_match("/Thursday/", $frase) OR preg_match("/Friday/", $frase) OR preg_match("/Sunday/", $frase) OR preg_match("/Saturday/", $frase) OR preg_match("/Monday/", $frase)) {

									$div_fecha = explode(" ", $frase);

									echo "$div_fecha[3]";

									if ($div_fecha[3] == "March") {	$mes_name = "03"; }
									if ($div_fecha[3] == "April") {	$mes_name = "04";}
									if ($div_fecha[3] == "May") { $mes_name = "05";	}
									if ($div_fecha[3] == "June") {	$mes_name = "06"; }
									if ($div_fecha[3] == "July") {	$mes_name = "07";} 
									if ($div_fecha[3] == "August") { $mes_name = "08"; }
									if ($div_fecha[3] == "September") { $mes_name = "09"; }
									if ($div_fecha[3] == "October") { $mes_name = "10"; }
									if ($div_fecha[3] == "November") { $mes_name = "11"; }
									if ($div_fecha[3] == "December") { $mes_name = "12"; }

									if (strlen($div_fecha[2]) == "1") {
										$dia_name = "0".$div_fecha[2];
									} else {
										$dia_name = $div_fecha[2];
									}

									$fecha_market = $div_fecha[4]."-".$mes_name."-".$dia_name;

								} else {						

				        			$division1 = explode("BST)", $frase);
				        				$division1_1 = trim($division1[0]);
				        				$division1_2 = trim($division1[1]);

				        			$division2 = explode("(", $division1_1);
				        				$EQUIPOS = trim($division2[0]);
				        				$HORA = trim($division2[1]);

				        				$division5 = explode(" v ", $EQUIPOS);

				        				$fecha_partido = "$fecha_market $HORA[0]$HORA[1]".":"."$HORA[3]$HORA[4]";

										$nuevafecha = strtotime ( '-5 hour' , strtotime ( $fecha_partido ) ) ;
										$date_market = date('Y-m-j',$nuevafecha);
										$time_market = date('H:i:s',$nuevafecha);	

										$date_time = $date_market." ".$time_market;

										echo "EQUIPOS: $EQUIPOS<br>FECHA: $date_time<br>";										

				        			$division3 = explode(" ", $division1_2);
				        				$DIV0 = trim($division3[0]);
				        				$DIV1 = trim($division3[1]);
				        				$division3_1 = trim($division3[2]);

				        			$division4 = explode(" ", $division3_1);
				        				$DIV2 = $division4[0];				        				

									$id_equipos_marquet = "";

									for ($i=0; $i < 2; $i++) { 
										$pos_rec = strpos($division5[$i], '/');

										$equipo1 = $division5[$i];

										if ($pos_rec === false) {
										    $con_part1 = "SELECT * FROM equipo WHERE id_wihi_equipo = '".$equipo1."'";
											$ex_con_part1 = $conexion->query($con_part1);
											$regs_con_part1 = $ex_con_part1->num_rows;

											if ($regs_con_part1 == '0') {
												$con_part2 = "INSERT INTO equipo (id_wihi_equipo, nombre_equipo) VALUES ('$equipo1','$equipo1')";
												$ex_con_part2 = $conexion->query($con_part2);
												if($ex_con_part2){ 													
													$id_equipo = $conexion->insert_id; 

													if ($i == 0) { $id_eq1_temp = $id_equipo; }

													$con_eq_li1 = "SELECT * FROM equipo_liga WHERE id_equipo = $id_equipo AND id_liga = $id_liga";
													$ex_con_eq_li1 = $conexion->query($con_eq_li1);
													$regs_con_eq_li1 = $ex_con_eq_li1->num_rows;

													if ($regs_con_eq_li1 == 0) {
														$con_eq_li2 = "INSERT INTO equipo_liga (id_equipo, id_liga) VALUES ('$id_equipo','$id_liga')";
														$ex_con_eq_li1 = $conexion->query($con_eq_li2);
													}
												} else { printf("Errormessage: %s\n", $conexion->error); }
											} else {
												$bus_con_part1 = $ex_con_part1->fetch_assoc();				            
							            		$id_equipo = $bus_con_part1["id_equipo"];

							            		if ($i == 0) { $id_eq1_temp = $id_equipo; }

							            		$con_eq_li1 = "SELECT * FROM equipo_liga WHERE id_equipo = $id_equipo AND id_liga = $id_liga";
												$ex_con_eq_li1 = $conexion->query($con_eq_li1);
												$regs_con_eq_li1 = $ex_con_eq_li1->num_rows;

												if ($regs_con_eq_li1 == 0) {
													$con_eq_li2 = "INSERT INTO equipo_liga (id_equipo, id_liga) VALUES ('$id_equipo','$id_liga')";
													$ex_con_eq_li1 = $conexion->query($con_eq_li2);
												}
											}
											if ($i == "0") {
												$id_equipos_marquet .= $id_equipo; 
											} else {
												$id_equipos_marquet .= ".".$id_equipo; 
											}
										} else {
										    $division5_2 = explode("/", $division5[$i]);
										    for ($j=0; $j < 2; $j++) {
										    	echo "$division_5_2[$j]<br>";
										    }
										}															
									} 							

									$id_partido_inicial = $date_market."!".$id_equipos_marquet."!".$time_market;

									$con_market1 = "SELECT * FROM p_futbol WHERE url = '$url_p_futbol'";
									$ex_con_market1 = $conexion->query($con_market1);
									$regs_con_market1 = $ex_con_market1->num_rows;

									if ($regs_con_market1 == '0') {
										$con_market2 = "INSERT INTO p_futbol (id_wihi_partido, id_liga, fecha_inicio, disponibilidad, url) VALUES ('$id_partido_inicial','$id_liga','$date_time','2017-01-01','$url_p_futbol')";
										$ex_con_market2 = $conexion->query($con_market2);
										if($ex_con_market2){ 
											$id_p_futbol = $conexion->insert_id; 
											echo "GUARDADO: $id_p_futbol<br><br>";
										} else { 
											printf("Errormessage: %s\n", $conexion->error); 
										}
									} else {											
										$bus_con_market1 = $ex_con_market1->fetch_assoc();				            
					            		$id_p_futbol = $bus_con_market1["id_partido"];
					            		$id_wihi_p = $bus_con_market1["id_wihi_partido"]; 

					            		$hora_existe = $bus_con_market1["fecha_inicio"];

					            		echo "<br>";

					            		$hoy = date("Y-m-d H:i:s");

					            		if ($id_categoria == '22' AND $hora_existe > $hoy) {
						            		echo "Juego aún activo. Béisbol.<br><br>";
					            		} else {
					            			if ($hora_existe != $date_time) {
										 		$consulta_h2 = "UPDATE p_futbol SET fecha_inicio='$date_time' WHERE id_partido='$id_p_futbol'";
										 		if ($ej_c_h = $conexion->query($consulta_h2)) {
										 			echo "Se modificó la hora.<br><br>";
										 		}
										 	}
					            		}									 	
					            	} 	          	
								}
							}			 
						}
					}
				} else {	

					echo "Un solo juego<br>";				

					$nombre_tipo = ""; $url_partido=""; $url_tipo = "";	

					$cada_tipo = $pokemon_xpath->query('//div[@class="subContainer"] //table[@class="listOne"] //td[@class="event"]');

					if($cada_tipo->length > 0){
						foreach($cada_tipo as $row){											

							$nombre_tipo = utf8_decode(addslashes($row->nodeValue));
							$nombre_tipo = preg_replace('/\s\s+/', ' ', $nombre_tipo);	
							$nombre_tipo = trim($nombre_tipo);

							$links=$row->getElementsByTagName('a');
							$i = 0;					

							foreach($links as $a) {								
						        $url_partido = addslashes($a->getAttribute('href'));

						        if ($nombre_tipo == "Money Line" OR $nombre_tipo == "Match Result") {
						        	$url_tipo = $url_partido;						        	
						        }
						        $i++;
						    }
						}
					}	

					$title_h1 = $pokemon_xpath->query('//h1');

					if($title_h1->length > 0){
						foreach($title_h1 as $titular){
							$nombre_enc = utf8_decode(addslashes($titular->nodeValue));
							$nombre_enc = substr($nombre_enc, 1);							

							$juego_linea = explode(": ", $nombre_enc);

							$equipos = explode(" v ", $juego_linea[0]);

							$equipo1 = trim($equipos[0]);
							$equipo2 = trim($equipos[1]);

							if ($equipo1 != "") {
							    $con_part1 = "SELECT * FROM equipo WHERE id_wihi_equipo = '".$equipo1."'";
								$ex_con_part1 = $conexion->query($con_part1);
								$regs_con_part1 = $ex_con_part1->num_rows;

								if ($regs_con_part1 == '0') {
									$con_part2 = "INSERT INTO equipo (id_wihi_equipo, nombre_equipo) VALUES ('$equipo1','$equipo1')";
									$ex_con_part2 = $conexion->query($con_part2);
									if($ex_con_part2){ 													
										$id_equipo1 = $conexion->insert_id; 								

										$con_eq_li1 = "SELECT * FROM equipo_liga WHERE id_equipo = $id_equipo1 AND id_liga = $id_liga";
										$ex_con_eq_li1 = $conexion->query($con_eq_li1);
										$regs_con_eq_li1 = $ex_con_eq_li1->num_rows;

										if ($regs_con_eq_li1 == 0) {
											$con_eq_li2 = "INSERT INTO equipo_liga (id_equipo, id_liga) VALUES ('$id_equipo1','$id_liga')";
											$ex_con_eq_li1 = $conexion->query($con_eq_li2);
										}
									} else { printf("Errormessage: %s\n", $conexion->error); }
								} else {
									$bus_con_part1 = $ex_con_part1->fetch_assoc();				            
				            		$id_equipo1 = $bus_con_part1["id_equipo"];		            		

				            		$con_eq_li1 = "SELECT * FROM equipo_liga WHERE id_equipo = $id_equipo1 AND id_liga = $id_liga";
									$ex_con_eq_li1 = $conexion->query($con_eq_li1);
									$regs_con_eq_li1 = $ex_con_eq_li1->num_rows;

									if ($regs_con_eq_li1 == 0) {
										$con_eq_li2 = "INSERT INTO equipo_liga (id_equipo, id_liga) VALUES ('$id_equipo1','$id_liga')";
										$ex_con_eq_li1 = $conexion->query($con_eq_li2);
									}
								}
							}

							if ($equipo2 != "") {
							    $con_part1 = "SELECT * FROM equipo WHERE id_wihi_equipo = '".$equipo2."'";
								$ex_con_part1 = $conexion->query($con_part1);
								$regs_con_part1 = $ex_con_part1->num_rows;

								if ($regs_con_part1 == '0') {
									$con_part2 = "INSERT INTO equipo (id_wihi_equipo, nombre_equipo) VALUES ('$equipo2','$equipo2')";
									$ex_con_part2 = $conexion->query($con_part2);
									if($ex_con_part2){ 													
										$id_equipo = $conexion->insert_id; 								

										$con_eq_li1 = "SELECT * FROM equipo_liga WHERE id_equipo = $id_equipo2 AND id_liga = $id_liga";
										$ex_con_eq_li1 = $conexion->query($con_eq_li1);
										$regs_con_eq_li1 = $ex_con_eq_li1->num_rows;

										if ($regs_con_eq_li1 == 0) {
											$con_eq_li2 = "INSERT INTO equipo_liga (id_equipo, id_liga) VALUES ('$id_equipo2','$id_liga')";
											$ex_con_eq_li1 = $conexion->query($con_eq_li2);
										}
									} else { printf("Errormessage: %s\n", $conexion->error); }
								} else {
									$bus_con_part1 = $ex_con_part1->fetch_assoc();				            
				            		$id_equipo2 = $bus_con_part1["id_equipo"];		            		

				            		$con_eq_li1 = "SELECT * FROM equipo_liga WHERE id_equipo = $id_equipo2 AND id_liga = $id_liga";
									$ex_con_eq_li1 = $conexion->query($con_eq_li1);
									$regs_con_eq_li1 = $ex_con_eq_li1->num_rows;

									if ($regs_con_eq_li1 == 0) {
										$con_eq_li2 = "INSERT INTO equipo_liga (id_equipo, id_liga) VALUES ('$id_equipo2','$id_liga')";
										$ex_con_eq_li1 = $conexion->query($con_eq_li2);
									}
								}
							}

							$id_equipos_marquet = $id_equipo1.".".$id_equipo2;

							$cada_hora = $pokemon_xpath->query('//p[@class="raceTimeContainer"]');

							if($cada_hora->length > 0){
								foreach($cada_hora as $fecha_hora){
									$fecha_enc = utf8_decode(addslashes($fecha_hora->nodeValue));
									$hora_sep = explode(" ", $fecha_enc);

									if ($hora_sep[2] == "March") {	$mes_name = "03"; } 
									if ($hora_sep[2] == "April") {	$mes_name = "04";} 
									if ($hora_sep[2] == "May") { $mes_name = "05";	}
									if ($hora_sep[2] == "June") {	$mes_name = "06"; } 
									if ($hora_sep[2] == "July") {	$mes_name = "07";}
									if ($hora_sep[2] == "August") { $mes_name = "08"; }
									if ($hora_sep[2] == "September") { $mes_name = "09"; }
									if ($hora_sep[2] == "October") { $mes_name = "10"; }

									$fechia = $hora_sep[1]."-".$mes_name."-".$hora_sep[3];
									$fecha_partido = $fechia." ".$hora_sep[5];

									$nuevafecha = strtotime ( '-5 hour' , strtotime ( $fecha_partido ) ) ;
									$date_market = date('Y-m-j',$nuevafecha);
									$time_market = date('H:i:s',$nuevafecha);	

									$date_time = $date_market." ".$time_market;

									echo "EQUIPOS: $equipo1 v $equipo2<br>FECHA: $date_time<br>";

									$id_partido_inicial = $date_market."!".$id_equipos_marquet."!".$time_market;

									$con_market1 = "SELECT * FROM p_futbol WHERE url = '$url_tipo'";
									$ex_con_market1 = $conexion->query($con_market1);
									$regs_con_market1 = $ex_con_market1->num_rows;

									if ($regs_con_market1 == '0') {
										$con_market2 = "INSERT INTO p_futbol (id_wihi_partido, id_liga, fecha_inicio, disponibilidad, url) VALUES ('$id_partido_inicial','$id_liga','$date_time','2017-01-01','$url_tipo')";
										$ex_con_market2 = $conexion->query($con_market2);
										if($ex_con_market2){ 
											$id_p_futbol = $conexion->insert_id; 
											echo "GUARDADO: $id_p_futbol<br><br>";
										} else { 
											printf("Errormessage: %s\n", $conexion->error); 
										}
									} else {											
										$bus_con_market1 = $ex_con_market1->fetch_assoc();				            
					            		$id_p_futbol = $bus_con_market1["id_partido"];
					            		$id_wihi_p = $bus_con_market1["id_wihi_partido"]; 

					            		$hora_existe = $bus_con_market1["fecha_inicio"];

					            		echo "<br>";

					            		$hoy = date("Y-m-d H:i:s");

					            		if ($id_categoria == '22' AND $hora_existe > $hoy) {
						            		echo "Juego aún activo. Béisbol.<br><br>";
					            		} else {
					            			if ($hora_existe != $date_time) {
										 		$consulta_h2 = "UPDATE p_futbol SET fecha_inicio='$date_time' WHERE id_partido='$id_p_futbol'";
										 		if ($ej_c_h = $conexion->query($consulta_h2)) {
										 			echo "Se modificó la hora.<br><br>";
										 		}
										 	}
					            		}
					            	} 
								}
							}							
						}
					}			
				}			
			}
		}
	}
?>
<script type="text/javascript">
	$('#fixed-bar').fadeOut(1000);
</script>