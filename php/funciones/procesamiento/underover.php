<?php  

echo " <b>- BAJA / ALTA - </b><br>";

$ovun = "X";

$titulo = $pokemon_xpath->query('//table[@class="odds allOdds"] //tr');

if($titulo->length > 0){
	foreach($titulo as $row){					

		$liga_anme = utf8_decode(addslashes($row->nodeValue));
		$liga_name = preg_replace('/\s\s+/', ' ', $liga_anme);				

		$tedes=$row->getElementsByTagName('td');

		foreach($tedes as $td) {

			echo "$ovun<br>";

			$recuerda = "";
			$value_competitor = utf8_decode(addslashes($td->nodeValue)); 
			$value365 = '';

	        $url_td = addslashes($td->getAttribute('title'));
	        $class_td = addslashes($td->getAttribute('class'));

	        $clase = explode(" ", $class_td);

	        if ($clase[0] == "Competitor") {					        	
	        	$value_competitor = utf8_decode(addslashes($td->nodeValue)); 
	        	$value_competitor = trim($value_competitor);	

	        	$division = explode("er", $value_competitor);		
	        	$indice = substr($division[1], 1);		

	        	if ($division[0] == "Ov") {
	        		$etiqueta = "o";
	        	} elseif ($division[0] == "Und") {
	        		$etiqueta = "u";
	        	}     		 

	        	$cuota = $etiqueta.$indice;   		    			        	
	        }	

	        if ($clase[0] == "bindHover"){
	        	if ($value_competitor != "" and ($ovun == "X" or $ovun == "Y")) {   	        	
			        $value365 = preg_replace('/\s\s+/', ' ', $td->nodeValue);	

			        echo "<br>$value365 $ovun ( $cuota )<br>";   

			        if ($ovun == "Y") {
						$recuerda = "cambiarY";
						echo "Recuerda: Cambiar Y<br>";
					}     

					if ($value365 != "" and $ovun == "X") {
						$div_div = explode("/", $value365);

						echo "Div1: $div_div[0] <br>";

						if (!isset($div_div[1])) {
							$div_div[1] = 1;
						}

						$decimal_odd = ($div_div[0] / $div_div[1])+1;

						echo "DEC: $decimal_odd <br>";

						if ($ovun == "X" and ($decimal_odd > 1.29 AND $decimal_odd < 3.4)) {
							$ovun = "Y";
						} else {
							$ovun = "Z";
							$value_competitor = "";
							break;
						}
					}

			        if ($ovun == "Y") { 

			        	$id_participante = $id_partido."!".$id_partido."!".$id_ta."!".$cuota;

			        	$con_parti1 = "SELECT * FROM participante WHERE id_wihi_participante = '$id_participante' AND id_ta = '$id_ta' AND id_partido = '$id_partido'";
						$ex_con_parti1 = $conexion->query($con_parti1);
						$regs_con_parti1 = $ex_con_parti1->num_rows;

						if ($regs_con_parti1 == 0) {										

							$con_parti2 = "INSERT INTO participante (id_wihi_participante, id_partido, id_equipo1, id_ta, indice, dividendo) VALUES ('$id_participante','$id_partido','$id_equipo','$id_ta','$cuota','$value365')";
							if ($ex_con_parti2 = $conexion->query($con_parti2)) {
								echo "Registrado a $value365 de <br>";
								$value_competitor = "";										
								$value365 = "";		

								if ($recuerda == "cambiarY") { $ovun = "X";	} break;		
							}							
						} else {
							$prt_con_parti1 = $ex_con_parti1->fetch_assoc();	
							$dividendo_ext1 = $prt_con_parti1["dividendo"];

							$id_participante = $id_partido."!".$id_partido."!".$id_ta."!".$cuota;

		            		if ($dividendo_ext1 != $value365) {
		            			$mod_con_parti1 = "UPDATE participante SET dividendo='$value365' WHERE id_wihi_participante='$id_participante'";
		            			echo "$mod_con_parti1 <br>";
		            			if ($ej_cons_parti1 = $conexion->query($mod_con_parti1)) {
		            				echo "<br> Modificado $texto de $dividendo_ext1 a $dividendo ";
		            				$value_competitor = "";				            				
									$value365 = "";	

									if ($recuerda == "cambiarY") { $ovun = "X"; } break;			
		            			}
		            		} else {
		            			echo "<br> Cuotas de partido sin modificar";
		            			$value_competitor = "";				            				
								$value365 = "";	
								
								if ($recuerda == "cambiarY") { $ovun = "X";	} break;		
		            		}				            		
						}    	
			        }					        
			    } elseif ($ovun == "Z") {
					$ovun = "X";
					$value_competitor = "";
					break;
				} 
			}
	    }									
	}	
}

$upd_con_parti1 = "UPDATE url_partido SET guia = '' WHERE id_url_partido = '$url_id'";
if ($ej_upd_parti1 = $conexion->query($upd_con_parti1)) {								
   echo "<script type='text/javascript'>window.parent.$('#dv_invisible2').load('php/principales/ronda_logros.php?IDE=$id_liga_get&actual_part=$nuevo_upd&generate=$codigo&codigo2=$codigo2');</script>";
}

?>