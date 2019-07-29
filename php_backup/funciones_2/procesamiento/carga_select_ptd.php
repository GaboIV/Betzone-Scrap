<?php 

	if (file_exists("../../conexion.php")) {
	    require_once("../../conexion.php");
	} else {
	    require_once("../../../conexion.php");
	}

	$cargador = $_GET["cargar"];	

	if ($cargador == "liga") { $id_deporte = $_GET["iddeporte"]; ?>
		<select id="liga_ptd" class="select_cat_ptd" name="liga_ptd">   
		<option value="0">Todos las ligas</option>         
		    <?php                        
		        $orden = "SELECT * FROM liga WHERE id_categoria = '$id_deporte' ORDER BY id_pais";
		        $ejecutar_orden = $conexion->query($orden);
		        while ($respuesta = $ejecutar_orden->fetch_assoc()) {
		            $nombre_liga = $respuesta["nombre_liga"];
		            $id_liga = $respuesta["id_liga"];
		            echo "<option value='$id_liga'";	  
		            echo ">$nombre_liga</option>"; 
		        }
		    ?>
		</select>
	<?php } ?> 

	<?php if ($cargador == "equipo") { $id_liga = $_GET["idliga"]; ?>
		<select id="equipos_ptd" class="select_cat_ptd" name="equipos_ptd">   
		<option value="0">Todos los equipos</option>         
		    <?php      

		    	$orden = "SELECT * FROM equipo_liga INNER JOIN equipo ON equipo_liga.id_equipo=equipo.id_equipo WHERE equipo_liga.id_liga = '$id_liga' ORDER BY equipo.nombre_equipo ASC";  
		        $ejecutar_orden = $conexion->query($orden);
		        while ($respuesta = $ejecutar_orden->fetch_assoc()) {
		            $nombre_equipo = $respuesta["nombre_equipo"];
		            $id_equipo = $respuesta["id_equipo"];
		            echo "<option value='$id_equipo'";	  
		            echo ">$nombre_equipo</option>"; 
		        }
		    ?>
		</select>
	<?php } ?> 

	<?php if ($cargador == "fecha") { $id_equipo = $_GET["idequipo"]; $criterio1 = "!".$id_equipo."."; $criterio2 = ".".$id_equipo."!";?>
		<select id="fecha_ptd" class="select_cat_ptd" name="fecha_ptd">   
		<option value="0">Todos las fechas</option>         
		    <?php      

		    	$orden = "SELECT * FROM p_futbol WHERE upper(id_wihi_partido) LIKE upper('%" . $criterio1 . "%' ) OR upper(id_wihi_partido) LIKE upper('%" . $criterio2 . "%' )";  
		        $ejecutar_orden = $conexion->query($orden);
		        while ($respuesta = $ejecutar_orden->fetch_assoc()) {
		            $fecha_inicio = $respuesta["fecha_inicio"];
		            $id_partido = $respuesta["id_partido"];
		            echo "<option value='$id_partido'";	  
		            echo ">$fecha_inicio</option>"; 
		        }
		    ?>
		</select>
	<?php } ?> 

	<script type="text/javascript">
		
		/*CARGA DE SELECT*/

	    $("#liga_ptd").change(function(){
	    	var value = this.value;
	    	$(".capa03_ptd").html("Cargando...");
	    	$(".capa04_ptd").html("");
	    	$(".capa03_ptd").load("php/principales/procesamiento/carga_select_ptd.php?cargar=equipo&idliga="+value);
	    	window.parent.$(".capa_cargadora").fadeIn(1000);
	    	window.parent.$(".total_eq").load("php/principales/solo_partidos.php?cargar=equipo&idliga="+value);
	    });

	    $("#equipos_ptd").change(function(){
	    	var value = this.value;
	    	$(".capa04_ptd").html("Cargando...");
	    	$(".capa04_ptd").load("php/principales/procesamiento/carga_select_ptd.php?cargar=fecha&idequipo="+value)
	    	window.parent.$(".capa_cargadora").fadeIn(1000);
	    	window.parent.$(".total_eq").load("php/principales/solo_partidos.php?cargar=fecha&idequipo="+value);
	    });

	</script>