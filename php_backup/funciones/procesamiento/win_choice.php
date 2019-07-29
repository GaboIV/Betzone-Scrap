<?php  

$titulo = $pokemon_xpath->query('//table[@class="odds allOdds"] //tr');

echo " <b>- GANADOR - </b><br>";

if($titulo->length > 0){
	foreach($titulo as $row){					

		$liga_anme = utf8_decode(addslashes($row->nodeValue));
		$liga_name = preg_replace('/\s\s+/', ' ', $liga_anme);				

		$tedes=$row->getElementsByTagName('td');

		foreach($tedes as $td) {

			$value_competitor = utf8_decode(addslashes($td->nodeValue)); 	

			$value365 = '';
			$proveedor = '';

	        $url_td = addslashes($td->getAttribute('title'));
	        $class_td = addslashes($td->getAttribute('class'));

	        if ($class_td == "Competitor") {					        	
	        	$value_competitor = addslashes($td->nodeValue); 
	        	$value_competitor = trim($value_competitor);				        		        	

	        	$con_part1 = "SELECT * FROM equipo WHERE id_wihi_equipo = '$value_competitor'";
	        	echo "<br><br>$con_part1 <br><br>";
				$ex_con_part1 = $conexion->query($con_part1);
				$regs_con_part1 = $ex_con_part1->num_rows;

				if ($regs_con_part1 == '0') {
					$con_part2 = "INSERT INTO equipo (id_wihi_equipo, nombre_equipo) VALUES ('$value_competitor','$value_competitor')";
					$ex_con_part2 = $conexion->query($con_part2);
					if($ex_con_part2){ 													
						$id_equipo = $conexion->insert_id; 

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

            		echo "*".$value_competitor."* ";	
        		}			        	
	        }						        

	        if ($value_competitor != "") {   
	        	if ($url_td == "Bet 365") { 
		        	$value365 = preg_replace('/\s\s+/', ' ', $td->nodeValue); 
		        	$proveedor = "Bet365";						        	
		        } elseif ($url_td == "BetVictor") { 
		        	$value365 = preg_replace('/\s\s+/', ' ', $td->nodeValue); 
		        	$proveedor = "BetVictor";						        	
		        } elseif ($url_td == "Paddy Power") { 
		        	$value365 = preg_replace('/\s\s+/', ' ', $td->nodeValue); 
		        	$proveedor = "Paddy Power";						        	
		        } elseif ($url_td == "Marathonbet.co.uk") { 
		        	$value365 = preg_replace('/\s\s+/', ' ', $td->nodeValue); 
		        	$proveedor = "Marathonbet";						        	
		        } elseif ($url_td == "Betfair") { 
		        	$value365 = preg_replace('/\s\s+/', ' ', $td->nodeValue); 
		        	$proveedor = "Betfair";						        	
		        } elseif ($url_td == "Coral") { 
		        	$value365 = preg_replace('/\s\s+/', ' ', $td->nodeValue); 
		        	$proveedor = "Coral";						        	
		        }

		        if ($value365 != "") { 

		        	$id_participante = $id_equipo."!".$id_partido."!".$id_ta;

		        	$con_parti1 = "SELECT * FROM participante WHERE id_wihi_participante = '$id_participante' AND id_ta = '$id_ta' AND id_partido = '$id_partido'";
					$ex_con_parti1 = $conexion->query($con_parti1);
					$regs_con_parti1 = $ex_con_parti1->num_rows;

					if ($regs_con_parti1 == 0) {										

						$con_parti2 = "INSERT INTO participante (id_wihi_participante, id_partido, id_equipo1, id_ta, dividendo,vinculo,proveedor) VALUES ('$id_participante','$id_partido','$id_equipo','$id_ta','$value365','1','$proveedor')";
						if ($ex_con_parti2 = $conexion->query($con_parti2)) {
							echo " Registrado a $value365 (Provee: $proveedor) <br>";
							$value_competitor = "";										
							$value365 = "";		
							break;					
						}							
					} else {
						$prt_con_parti1 = $ex_con_parti1->fetch_assoc();	
						$dividendo_ext1 = $prt_con_parti1["dividendo"];

						$id_participante = $id_equipo."!".$id_partido."!".$id_ta;

	            		if ($dividendo_ext1 != $value365) {
	            			$mod_con_parti1 = "UPDATE participante SET dividendo='$value365', proveedor='$proveedor' WHERE id_wihi_participante='$id_participante' AND id_ta = '$id_ta' AND id_partido = '$id_partido'";
	            			echo "$mod_con_parti1 <br>";
	            			if ($ej_cons_parti1 = $conexion->query($mod_con_parti1)) {
	            				echo " Modificado $texto de $dividendo_ext1 a $dividendo (Provee: $proveedor) <br>";
	            				$value_competitor = "";				            				
								$value365 = "";	
								break;							
	            			}
	            		} else {
	            			echo " Cuotas de partido sin modificar <br>";
	            			$value_competitor = "";				            				
							$value365 = "";	
							break;						
	            		}				            		
					} 
		        }				        
		    }						      
	    }	    
	}
}

$upd_con_parti1 = "UPDATE url_partido SET guia = '' WHERE id_url_partido = '$url_id'";
if ($ej_upd_parti1 = $conexion->query($upd_con_parti1)) {								
    echo "<script type='text/javascript'>window.parent.$('#3').load('ronda_logros.php?IDE=$id_liga_get&actual_part=$nuevo_upd&generate=$codigo&codigo2=$codigo2');</script>";
}

?>