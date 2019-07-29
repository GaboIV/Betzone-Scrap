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

		switch ($name_deporte) {

		    case "football":
		        $id_categoria = "21";
		        $id_ta = "1";
		        break;
		    case "baseball":
		        $id_categoria = "22";
		        $id_ta = "4";
		        break;		
		    case "basketball":
		        $id_categoria = "23";
		        $id_ta = "2";
		        break;
		    case "tennis":
		        $id_categoria = "24";
		        $id_ta = "6";
		        break;
		    case "american-football":
		        $id_categoria = "25";
		        $id_ta = "3";
		        break;
		    case "boxing":
		        $id_categoria = "29";
		        $id_ta = "5";
		        break;
		    case "ice-hockey":
		        $id_categoria = "26";
		        $id_ta = "7";
		        break;
		    case "rugby-union":
		        $id_categoria = "28";
		        $id_ta = "13";
		        break;
		}	

		


		echo "Deporte: $name_deporte (ID: $id_categoria) - Tipo de Apuesta: $id_ta<br>";
	}		

	$html = file_get_contents($url_liga);

	$pokemon_doc = new DOMDocument();

	libxml_use_internal_errors(TRUE);

	if(!empty($html)){

		$pokemon_doc->loadHTML($html);
		libxml_clear_errors();

		$pokemon_xpath = new DOMXPath($pokemon_doc);

		$titulo_liga = $pokemon_xpath->query('//div[@id="breadCrumbContainer"] //a');

		$titulo_juego = $pokemon_xpath->query('//div[@id="marketEvent"]');

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

				$liga_name = preg_replace('/\s\s+/', ' ', $nombre_enc);

				$div_league = explode(": ", $liga_name);

				$name_deporte_opt = trim($div_league[0]);	

				$name_deporte_uno = str_replace("-", " ", $name_deporte);

				$compra = strnatcasecmp($name_deporte_uno, $name_deporte_opt);

				if ($titulo_juego->length == 0) {
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

						        	$url_p_futbol = str_replace("fight-result", "fight-result-(draw-no-bet)", $url_p_futbol);


						        }
						        $i++;
						    }			    

							if ($frase != " ") {

								if (preg_match("/Tuesday/", $frase) OR preg_match("/Wednesday/", $frase) OR preg_match("/Thursday/", $frase) OR preg_match("/Friday/", $frase) OR preg_match("/Sunday/", $frase) OR preg_match("/Saturday/", $frase) OR preg_match("/Monday/", $frase)) {

									$div_fecha = explode(" ", $frase);

									$host= $_SERVER["HTTP_HOST"];

									if ($host == 'localhost') {
										$diadf = $div_fecha[3];
										$mesdf = $div_fecha[4];
										$anyodf = $div_fecha[5];
									} else {
										$diadf = $div_fecha[2];
										$mesdf = $div_fecha[3];
										$anyodf = $div_fecha[4];
									}

									echo "<br><br> MES: $diadf $mesdf $anyodf <br><br>";

									

									if ($mesdf == "January"  ) { $mes_name = "01"; }
									if ($mesdf == "February" ) { $mes_name = "02";}
									if ($mesdf == "March"    ) { $mes_name = "03"; }
									if ($mesdf == "April"    ) { $mes_name = "04";}
									if ($mesdf == "May"      ) { $mes_name = "05";	}
									if ($mesdf == "June"     ) { $mes_name = "06"; }
									if ($mesdf == "July"     ) { $mes_name = "07";} 
									if ($mesdf == "August"   ) { $mes_name = "08"; }
									if ($mesdf == "September") { $mes_name = "09"; }
									if ($mesdf == "October"  ) { $mes_name = "10"; }
									if ($mesdf == "November" ) { $mes_name = "11"; }
									if ($mesdf == "December" ) { $mes_name = "12"; }

									$fecha_market = $anyodf."-".$mes_name."-".$diadf;

								} else {		

									if (preg_match("/BST/", $frase)) {
										$division1 = explode("BST)", $frase);;
									} elseif (preg_match("/GMT/", $frase)) {
										$division1 = explode("GMT)", $frase);
									}

									$dividendos = explode(" ", $division1[1]);

									if ($id_categoria == '21' OR $id_categoria == '28') {							
										$div[0] = $dividendos[1];
										$div[1] = $dividendos[2];
										$div[2] = $dividendos[3];									
									} else {
										if ($id_categoria == '29') {
											$div[0] = $dividendos[1];
											$div[1] = $dividendos[3];
										} else {
											$div[0] = $dividendos[1];
											$div[1] = $dividendos[2];
										}
										
									}

									// for ($i=0; $i < count($div); $i++) { 
									// 	echo "DIV $i: $div[$i] <br>";
									// }

				        			$division1 = explode("BST)", $frase);
				        			$division1_1 = trim($division1[0]);

				        			$division2 = explode("(", $division1_1);
			        				$EQUIPOS = trim($division2[0]);
			        				$HORA = trim($division2[1]);

			        				$division5 = explode(" v ", $EQUIPOS);

			        				$fecha_partido = "$fecha_market $HORA[0]$HORA[1]".":"."$HORA[3]$HORA[4]";

									$nuevafecha = strtotime ( '-4 hour' , strtotime ( $fecha_partido ) ) ;
									$date_market = date('Y-m-d',$nuevafecha);
									$time_market = date('H:i:s',$nuevafecha);	

									$date_time = $date_market." ".$time_market;

									echo "EQUIPOS: $EQUIPOS<br>FECHA: $date_time<br>";		

									$url_p_futbol = $url_p_futbol.'#'.$date_market;						

									$id_equipos_marquet = "";

									for ($i=0; $i < 2; $i++) { 

										if ($id_categoria == '24') {
											$pos_rec = strpos($division5[$i], '/');
										} else {
											$pos_rec = false;
										}										

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
										    	echo "$division5_2[$j]<br>";
										    }
										}															
									} 	

									$lequipes = explode(".", $id_equipos_marquet);						

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

					            	if ($id_categoria == '21' OR $id_categoria == '28') {
					            		$codigo_part[0] = $lequipes[0]."!".$id_p_futbol."!".$id_ta;
					            		$codigo_part[1] = "35!".$id_p_futbol."!".$id_ta;
					            		$codigo_part[2] = $lequipes[1]."!".$id_p_futbol."!".$id_ta;
					            	} else {
					            		$codigo_part[0] = $lequipes[0]."!".$id_p_futbol."!".$id_ta;
					            		$codigo_part[1] = $lequipes[1]."!".$id_p_futbol."!".$id_ta;
					            	}	

					            	for ($i=0; $i < count($codigo_part); $i++) { 
	        					       	$dividendo = $div[$i];
	        					       	$codigo = $codigo_part[$i];

	        					       	$leque = explode("!", $codigo);

	        					       	$cons = "SELECT * FROM participante WHERE id_wihi_participante = '$codigo'"; 
	        					       	$ejecutar = $conexion->query($cons);
	        					       	$nro_regs = $ejecutar->num_rows;

	        					       	if ($nro_regs == '0') {
	        					       		$cons2 = "INSERT INTO participante (id_wihi_participante, id_partido, id_equipo1, id_ta, dividendo, vinculo, proveedor) VALUES ('$codigo','$id_p_futbol','$leque[0]','$id_ta','$dividendo', '1', 'odds.football-data')";
	        					       		$ex_cons2 = $conexion->query($cons2);
											if($ex_cons2){

											} else {
												echo "No se pudo registrar: $cons2 <br>";
											}
	        					       	} elseif ($nro_regs > 0) {
	        					       		$resultado = $ejecutar->fetch_assoc();

	        					       		$id_participante_rs = $resultado['id_participante'];

	        					       		if ($resultado['dividendo'] != $dividendo) {

	        					       			$consulta_34 = "UPDATE participante SET dividendo='$dividendo' WHERE id_participante='$id_participante_rs'";

												if ($ejecutar_consulta_34 = $conexion->query($consulta_34)) {
													echo "Dividendo cambiado<br><br>";
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
				}			
			}
		}
	}
?>
<script type="text/javascript">
	$('#fixed-bar').fadeOut(1000);
</script>