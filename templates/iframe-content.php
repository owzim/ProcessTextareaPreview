<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link type="text/css" href="<?php echo $styleUrl ?>" rel="stylesheet">
</head>
<body>


	<div id="TAP-content"></div>


	<?php foreach ($scripts as $script): ?>
	<script type="text/javascript" src="<?php echo $script ?>"></script>
	<?php endforeach ?>

	<script>

		owzim.TextareaPreview.initPreview($("#TAP-content"), <?php echo $params ?>);

	</script>

</body>
</html>




