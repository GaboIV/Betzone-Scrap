<?php

	$date_one = trim($_POST['dato_one']);
	$date_two = $_POST['dato_two'];

	echo "<br><br>$date_one<br>";

	$datos_txt = explode("-", $date_two);

	if ($date_one == 'agregar_img_th') {
		$input = $_GET['foto'];

		$infoFile=getimagesize($_FILES["$input"]['tmp_name']);

		if($infoFile[0]>=30 && $infoFile[1]>=30){
			$carpetero = "../../../imagenes/equipos";

		    if (file_exists($carpetero)) {
		        
		    } else {
		        mkdir($carpetero);		        
		    }   

		    if (copy($_FILES["$input"]['tmp_name'],"../../../imagenes/equipos/$date_two.png")) { ?>
		    	<script type='text/javascript'>
		    		window.parent.$('<?php echo "#image_th".$date_two ?>').html('<img src="imagenes/equipos/<?php echo $date_two ?>.png" height="50px">');
		    		window.parent.$('<?php echo "#div_botones".$date_two ?>').html('<button id="<?php echo "eliminar_img_th-".$date_two ?>" class="elim_img_th">Elim.</button>');
		    	</script>
		    <?php } 		    
		}
	}

	if ($date_one == 'eliminar_img_th') {
		if(unlink("../../../imagenes/equipos/$date_two.png")){ ?>
			<script type='text/javascript'>
	    		window.parent.$('<?php echo "#image_th".$date_two ?>').html('<img src="imagenes/equipos/Unknown.png" height="50px">');
	    		window.parent.$('<?php echo "#div_botones".$date_two ?>').html('<button id="<?php echo "agregar_img_th-".$date_two ?>" class="agregar_img_th">Agregar</button>');
	    	</script>
		<?php }
		return;
	}

?>