<!DOCTYPE html>
<html lang="pt-br,en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
	<title>UPLOAD CSV FILE</title>
</head>
	<body>
	<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
		Converter para UTF8?
		<select name="utf8Encode">
			<option value="1">SIM</option>
			<option value="0">NAO</option>
		</select>
		<br>
		<input type="file" name="file">
		<input type="submit" name="btn_submit" value="Upload File" />
	</form>

	<?php
		if ($fh = @fopen($_FILES['file']['tmp_name'], 'r+')) 
		{
			$encode = $_POST['utf8Encode'];
			$lines = array();
			while( ($row = fgetcsv($fh)) !== FALSE ) 
			{
				$lines[] =$row;
			}
			echo "<pre>";
			foreach ($lines as $key => $line) 
			{
				$result = ($encode==1)?utf8_encode($line[0]):$line[0];
				var_dump($result);
			}
		}
	?>
	</body>
</html>

<!-- 
o objetivo dessa página é possibilitar que você faça o upload de um CSV para inserir no banco ou trabalhar com o texto em tela sem a necessidade de salvar o arquivo fisico 
-->