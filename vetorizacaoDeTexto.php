<?php
class Vetor
{
	function preparaTexto($texto,$excNum)
	{
		$texto = strtolower($texto);
		if ($excNum=="checked") 
		{
			for ($i=0; $i <=9 ; $i++) 
			{ 
				$texto = str_replace($i, "", $texto);
			}
		}
		$texto = preg_replace('/[áàãâä]/ui', 'a', $texto);
		$texto = preg_replace('/[éèêë]/ui', 'e', $texto);
		$texto = preg_replace('/[íìîï]/ui', 'i', $texto);
		$texto = preg_replace('/[óòõôö]/ui', 'o', $texto);
		$texto = preg_replace('/[úùûü]/ui', 'u', $texto);
		$texto = preg_replace('/[ç]/ui', 'c', $texto);
		$texto = preg_replace('/[^a-z0-9]/i', '_', $texto);
		$texto = preg_replace('/_+/', '_', $texto); 
		$texto = str_replace(",", "", $texto);
		$texto = str_replace(",,", ",", $texto);//acho que nao funciona
		$texto = str_replace("	", " ", $texto);
		$texto = str_replace("  ", " ", $texto);
		$texto = str_replace(" ", ",", $texto);
		$texto = str_replace("_", ",", $texto);
		return $texto;

	}

	function tratamento($texto,$palavrasPorFrase=1,$txtExcluir="",$excNum)
	{
		$texto = $this->preparaTexto($texto,$excNum);
		$arrExcluir = $this->preparaTexto($txtExcluir,$excNum);
		$arrExcluir = explode(",",$arrExcluir);

		foreach ($arrExcluir as $key => $palavraASerRemovida) 
		{
			$palavraASerRemovida = ",".$palavraASerRemovida.",";
			$texto = str_replace($palavraASerRemovida, ",", $texto);
		}

		$array = explode(",", $texto);
		$txtFrase = NULL;
		$separador=NULL;

		foreach ($array as $key => $palavra) 
		{	
			$key +=1;
			$txtFrase .= $separador.$palavra;
			$separador = (($key%$palavrasPorFrase==0)&&($key<>0)) ? ",":" ";
		}

		$array = explode(",", $txtFrase);
		return $array;
	}

	function repeticoes($chave=null, $arr=null)
	{
		$result =(array_count_values($arr));
		return $result[$chave];
	}

	function listaRepeticoes($arrText)
	{
		$arrVerificado = array();
		$arrPalavraRepeticoes = array();

		foreach ($arrText as $key => $chave) 
		{
			if ((!in_array($chave, $arrVerificado))&&(!empty($chave))) 
			{
			$countRepeticoes = $this->repeticoes($chave,$arrText);
			$arrPalavraRepeticoes += array("$chave"  =>"$countRepeticoes");
			}
			array_push($arrVerificado, $chave);
		}
		asort($arrPalavraRepeticoes);
		return $arrPalavraRepeticoes;
	}
}

// --------------------------------------------------------------------------------------------------
$textoExemplo = "O Rato Roeu O rato roeu a roupa do rei de Roma, O rato roeu a roupa do rei da Rússia, O rato roeu a roupa do rodovalho… O rato a roer roía. E a rosa Rita ramalho Do rato a roer se ria. A rata roeu a rolha Da garrafa da rainha.";
$preposicoesExemplo = "por,a,para,de,em,o,pelo,ao,pro,do,no,a,pela,à,pra,da,na,os,pelos,aos,pros,dos,nos,as,pelas,às,pras,das,nas,um,dum,num,uma,duma,numa,uns,duns,nuns,umas,dumas,numas,ele,dele,nele,ela,dela,nela,eles,deles,neles,elas,delas,nelas,este,deste,neste,isto,disto,nisto,esse,desse,nesse,isso,disso,nisso,aquele,àquele,praquele,daquele,naquele,aquilo,àquilo,praquilo,daquilo,naquilo,boa tarde,bom dia, boa noite";

$texto = isset($_POST['texto'])?$_POST['texto']:$textoExemplo;
$minLetras = isset($_POST['minLetras'])?$_POST['minLetras']:3;
$minRepet = isset($_POST['minRepet'])?$_POST['minRepet']:2;
$palavrasPorFrase = isset($_POST['palavrasPorFrase'])?$_POST['palavrasPorFrase']:1;
$txtExcluir = isset($_POST['txtExcluir'])?$_POST['txtExcluir']: $preposicoesExemplo;
$excNum = isset($_POST['excNum'])?"checked":"";

$vetor = new Vetor;
$arrText = $vetor->tratamento($texto,$palavrasPorFrase,$txtExcluir,$excNum);
$arrPalavraRepeticoes = $vetor->listaRepeticoes($arrText);
?>

<form method="POST">
	TEXTO:<br><textarea rows="4" cols="100" name="texto"><?= $texto ?></textarea>
	<br>
	<br>Palavras para compor uma frase =<input type="number" name="palavrasPorFrase" value="<?= $palavrasPorFrase ?>"> OBS: 3 recomendado para textos grandes
	<br>
	<br>Minimo de letras em uma palavra <input type="number" name="minLetras" value="<?= $minLetras ?>"> OBS: 3 é o minimo recomendado
	<br>
	<br>Minimo de repeticoes no texto >= <input type="number" name="minRepet" value="<?= $minRepet ?>"> OBS: 2 é o minimo recomendado
	<br>
	<br>Excluir palavras/preposições:<br><textarea rows="4" cols="100" name="txtExcluir" placeholder="EX: AMOR,CAFE,CARRO"><?= $txtExcluir ?></textarea> 
	<br> 
	<br>Excluir números?<input type="checkbox" name="excNum" value="true" <?= $excNum ?>>
	<br>
	<br><input type="submit" name="enviar">
</form>

<?= "<br>Quantidade de caracteres analisados: ".strlen($texto); ?>
<?= "<br>Quantidade de palavras analisadas: ".count($arrText); ?>
<br><br>
<table border="1">
	<tr>
		<th>Frase</th>
		<th>Repetições</th>
	</tr>
	<?php
	$arrPalavraRepeticoes = array_reverse($arrPalavraRepeticoes);
	$valida=0;
	foreach ($arrPalavraRepeticoes as $palavra => $repeticoes) 
	{
		if ((strlen($palavra)>=$minLetras) && ($repeticoes >= $minRepet)) 
		{
			$valida = 1;
			echo "<tr>";
			echo "<td>$palavra</td>";
			echo "<td>$repeticoes</td>";
			echo "</tr>";
		}
	}

	if ($valida==0) 
	{
		echo "<h4> Tente mudar as configurações acima <br> Sugiro alterar o campo [Palavras na frase] para 1 dessa forma você consegue analisar palavra a palavra! </h4>";
	}
	?>
</table>

<br>
<br>

<div align="center" id="cloud" name="cloud">
	<button onClick="history.go(0);">Refresh Page</button>
	<br><br><br>
	<!-- <button onClick="window.location.reload();">reload Page</button> -->


<?php
foreach ($arrPalavraRepeticoes as $palavra => $repeticoes) 
	{
		if ((strlen($palavra)>=$minLetras) && ($repeticoes >= $minRepet)) 
		{
			$valida = 1;
			$repeticoes *=20;
			$possbilidade =  array(1,2,3,4);
			$posicoes = array_rand($possbilidade) == 1?"vertical-lr":"horintal-tb";
			$br = array_rand($possbilidade) == 1?"<br>":"";
			echo'<text align="center"  style="font-size: '.$repeticoes.'px; font-family: Impact;  
			writing-mode: '.$posicoes.';"> '
			.$palavra.
			' </text>'.$br;
		}
	}
?>
</div>
