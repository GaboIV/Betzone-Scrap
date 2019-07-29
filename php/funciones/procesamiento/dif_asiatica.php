<?php 

	echo " <b>- HANDICAP ASIÁTICO - </b><br>";

	$asian = "X";

	$titulo = $pokemon_xpath->query('//table[@class="odds allOdds"] //tr');

	if($titulo->length > 0){
		foreach($titulo as $row){	

			echo "*<br>TR: *";				

			$liga_anme = utf8_decode(addslashes($row->nodeValue));
			$class_tr = addslashes($row->getAttribute('class'));
			$liga_name = preg_replace('/\s\s+/', ' ', $liga_anme);				

			$tedes=$row->getElementsByTagName('td');

			foreach($tedes as $td) {

				$recuerda = "";
				$value_competitor = utf8_decode(addslashes($td->nodeValue)); 	
				$value365 = '';

				$url_td = addslashes($td->getAttribute('title'));
				$class_td = addslashes($td->getAttribute('class'));

				$clase = explode(" ", $class_td);

				if ($clase[0] == "Competitor") {					        	
					$value_competitor = utf8_decode(addslashes($td->nodeValue)); 
					$value_competitor = trim($value_competitor);

					if ($class_tr == "AO0") {
						$linea1 = explode($equipo1, $value_competitor);

						$con_part1 = "SELECT * FROM equipo WHERE id_wihi_equipo = '$equipo1'";
						$ex_con_part1 = $conexion->query($con_part1);
						$bus_con_part1 = $ex_con_part1->fetch_assoc();				            
						$id_equipo = $bus_con_part1["id_equipo"];

						$verificar_hand1 = strpos($linea1[1], ",");
						$verificar_hand2 = strpos($linea1[1], ",");

						if ($verificar_hand1 === false OR $verificar_hand2 === false) {
							$verificar_macho = strpos($linea1[1], "+");

							if ($verificar_macho === false) {
								$cuota = substr($linea1[1], 2);
							} else {
								$cuota = substr($linea1[1], 1);
							}
							echo "AO0 - $id_equipo ( $cuota ) ";        				
						} else {
							$cuota = substr($linea1[1], 1);
							echo "AO0C - $id_equipo ( $cuota ) ";			        		
						}	        			
					}

					if ($class_tr == "AO1") {
						$linea1 = explode($equipo2, $value_competitor);

						$con_part1 = "SELECT * FROM equipo WHERE id_wihi_equipo = '$equipo2'";
						$ex_con_part1 = $conexion->query($con_part1);
						$bus_con_part1 = $ex_con_part1->fetch_assoc();				            
						$id_equipo = $bus_con_part1["id_equipo"];

						$verificar_hand1 = strpos($linea1[1], ",");
						$verificar_hand2 = strpos($linea1[1], ",");

						if ($verificar_hand1 === false OR $verificar_hand2 === false) {
							$verificar_macho = strpos($linea1[1], "+");

							if ($verificar_macho === false) {
								$cuota = substr($linea1[1], 2);
							} else {
								$cuota = substr($linea1[1], 1);
							}

							echo "AO1 - $id_equipo ( $cuota ) ";
						} else {
							$cuota = substr($linea1[1], 1);
							echo "AO1C - $id_equipo ( $cuota ) ";
						}	        			
					}
				}
				if ($clase[0] == "bindHover") {
					if ($value_competitor != "" and ($asian == "X" or $asian == "Y")) {   	        	
						$value365 = preg_replace('/\s\s+/', ' ', $td->nodeValue);	    

						if ($asian == "Y") {
							$recuerda = "cambiarY";
						}

						if ($value365 != "" and $asian == "X") {
							$div_div = explode("/", $value365);

							if (!isset($div_div[1])) {
								$div_div[1] = 1;
							}

							$decimal_odd = ($div_div[0] / $div_div[1])+1;

							

							if ($asian == "X" and ($decimal_odd > 1.29 AND $decimal_odd < 3.41)) {
								$asian = "Y";
							} else {
								$asian = "Z";
								$value365 = "";
								$value_competitor = "";
								break;
							}

						}
						
						if ($asian == "Y") {
							
							$value_competitor = "";										
							$value365 = "";	

							if ($recuerda == "cambiarY") { $asian = "X"; } break;
							
							break;																							
							 
						}		        
					} elseif ($asian == "Z") {
						$asian = "X";
						$value_competitor = "";
						$value365 = "";
						break;
					}
				}	

				echo "($decimal_odd) ($asian)";				
			}										
		}	
	}

	

$upd_con_parti1 = "UPDATE url_partido SET guia = '' WHERE id_url_partido = '$url_id'";
	if ($ej_upd_parti1 = $conexion->query($upd_con_parti1)) {								
	    echo "<script type='text/javascript'>window.parent.$('#dv_invisible3').load('php/principales/ronda_logros.php?IDE=$id_liga_get&actual_part=$nuevo_upd&generate=$codigo&codigo2=$codigo2');</script>";
	}

 ?>