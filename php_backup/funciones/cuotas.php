<!DOCTYPE html>
<html>
<head>
	<title>Cargar datos de Angular</title>
	<script src="../../js/jq.js"></script>
</head>
<body>

	<style type="text/css">
		.line {
			background: red;
			display: inline-block;
			width: 33%;
			vertical-align: top;
		}
	</style>
	
	<div>
		<div id="1" class="line">
			1
		</div><div id="2" class="line">
			2
		</div><div id="3" class="line">
			3
		</div>
	</div>

	<?php 
		$id_liga = $_GET['IDE'];
	?>

	<script type="text/javascript">
		var id_liga = <?= json_encode($id_liga) ?>;
		$("#1").load("tipos_por_partido.php?IDE=" + id_liga + "&total_parts=0&actual_part=0");
	</script>	
</body>
</html>