<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link type="text/css" href="<?=$styleUrl?>" rel="stylesheet">
</head>
<body>
	
	
	<div id="TAP-content"></div>
		
	
	<?php foreach ($scripts as $script): ?>	
	<script type="text/javascript" src="<?=$script?>"></script>
	<?php endforeach ?>

	<script>
		
		owzim.TextareaPreview.initPreview($("#TAP-content"), <?=$params?>);
		
	</script>

</body>
</html>




