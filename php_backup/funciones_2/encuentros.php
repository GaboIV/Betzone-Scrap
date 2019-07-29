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
		$("#1").load("swt_xml.php?IDE="+ id_liga )
	</script>	
</body>
</html>