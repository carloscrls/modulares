<?php
class Vetor
{
	function preparaTexto($texto,$excNum="false")
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
		$texto = str_replace("	", " ", $texto);
		$texto = str_replace("  ", " ", $texto);
		$texto = str_replace(" ", ",", $texto);
		$texto = str_replace("_", ",", $texto);
		return $texto;
	}

	function mantemPalavrasExclusivas($arrOriginal,$txtManter)
	{
		$texto="";
		$arrManter = explode(",", $txtManter);
		foreach ($arrOriginal as $key => $palavraOriginal) 
		{
			foreach ($arrManter as $key => $palavraManter) 
			{
				if ($palavraOriginal == $palavraManter) 
				{
					$texto .=$palavraOriginal.$separador;
				}
				$separador=",";
			}
		}
		$arrModificado = explode(",", $texto);
		return $arrModificado;
	}

	function vetoriza($texto,$palavrasPorFrase=1,$txtExcluir="",$excNum,$txtManter="")
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
		if (!empty($txtManter)) 
		{
			$txtManter = $this->preparaTexto($txtManter);
			$array = $this->mantemPalavrasExclusivas($array,$txtManter);
		}
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

	function contaRepeticoes($chave=null, $arr=null)
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
				$countRepeticoes = $this->contaRepeticoes($chave,$arrText);
				$arrPalavraRepeticoes += array("$chave"  =>"$countRepeticoes");
			}
			array_push($arrVerificado, $chave);
		}
		asort($arrPalavraRepeticoes);
		return $arrPalavraRepeticoes;
	}
}

// <!--------------------------------------FORMULARIO ENTRADA-------------------------------------------->
$textoExemplo = "O Rato Roeu O rato roeu a roupa do rei de Roma, O rato roeu a roupa do rei da Rússia, O rato roeu a roupa do rodovalho… O rato a roer roía. E a rosa Rita ramalho Do rato a roer se ria. A rata roeu a rolha Da garrafa da rainha.";
$preposicoesExemplo = "por,a,para,de,em,o,pelo,ao,pro,do,no,a,pela,à,pra,da,na,os,pelos,aos,pros,dos,nos,as,pelas,às,pras,das,nas,um,dum,num,uma,duma,numa,uns,duns,nuns,umas,dumas,numas,ele,dele,nele,ela,dela,nela,eles,deles,neles,elas,delas,nelas,este,deste,neste,isto,disto,nisto,esse,desse,nesse,isso,disso,nisso,aquele,àquele,praquele,daquele,naquele,aquilo,àquilo,praquilo,daquilo,naquilo,boa tarde,bom dia, boa noite";

$texto = isset($_POST['texto'])?$_POST['texto']:$textoExemplo;
$minLetras = isset($_POST['minLetras'])?$_POST['minLetras']:3;
$minRepet = isset($_POST['minRepet'])?$_POST['minRepet']:2;
$palavrasPorFrase = isset($_POST['palavrasPorFrase'])?$_POST['palavrasPorFrase']:1;
$txtExcluir = isset($_POST['txtExcluir'])?$_POST['txtExcluir']: $preposicoesExemplo;
$excNum = isset($_POST['excNum'])?"checked":"";
$gNuvem = isset($_POST['gNuvem'])?"checked":"false";
$txtManter = isset($_POST['txtManter'])?$_POST['txtManter']:null;

$vetor = new Vetor;
$arrText = $vetor->vetoriza($texto,$palavrasPorFrase,$txtExcluir,$excNum,$txtManter);
$arrPalavraRepeticoes = $vetor->listaRepeticoes($arrText);
?>

<form method="POST">
	TEXTO:
	<br><textarea rows="4" cols="100" name="texto"><?= $texto ?></textarea>
	<br>
	<br>Palavras para compor uma frase =<input type="number" name="palavrasPorFrase" value="<?= $palavrasPorFrase ?>"> OBS: 3 recomendado para textos grandes
	<br>
	<br>Minimo de letras em uma palavra <input type="number" name="minLetras" value="<?= $minLetras ?>"> OBS: 3 é o minimo recomendado
	<br>
	<br>Minimo de repeticoes no texto >= <input type="number" name="minRepet" value="<?= $minRepet ?>"> OBS: 2 é o minimo recomendado
	<br>
	<br>Excluir palavras/preposições (Separar as palavras por virgula!):<br><textarea rows="4" cols="100" name="txtExcluir" placeholder="EX: AMOR,CAFE,CARRO"><?= $txtExcluir ?></textarea> 
	<br>
	<br>Manter somente essas palavras (Separar as palavras por virgula!):<br><textarea rows="4" cols="100" name="txtManter" placeholder="EX:RATO,REI,ROER"><?= $txtManter ?></textarea> 
	<br>  
	<br>Excluir números?<input type="checkbox" name="excNum" value="true" <?= $excNum ?>>
	<br>  
	<br>Gerar nuvem?<input type="checkbox" name="gNuvem" value="true" <?= $gNuvem ?>>
	<br>
	<br><input type="submit" name="enviar">
</form>

<!--------------------------------RESULTADO -------------------------------------------------->
<?= "<br>Quantidade de caracteres analisados: ".strlen($texto); ?>
<?= "<br>Quantidade de palavras analisadas: ".count($arrText); ?>
<br><br>
<table border="1">
	<tr>
		<th>#</th>
		<th>Palavra/Frase</th>
		<th>Repetições</th>
	</tr>
	<?php
	$arrPalavraRepeticoes = array_reverse($arrPalavraRepeticoes);
	$valida=0;
	$TotalRepeticoes=0;
	foreach ($arrPalavraRepeticoes as $palavra => $repeticoes) 
	{
		if ((strlen($palavra)>=$minLetras) && ($repeticoes >= $minRepet)) 
		{
			$valida += 1;
			$TotalRepeticoes += $repeticoes;
			echo "<tr>";
			echo "<td>$valida</td>";
			echo "<td>$palavra</td>";
			echo "<td>$repeticoes</td>";
			echo "</tr>";
		}
	}
	echo "<tr>";
	echo "<td>#</td>";
	echo "<td>TOTAL</td>";
	echo "<td>$TotalRepeticoes</td>";
	echo "</tr>";
	if ($valida==0) 
	{
		echo "<h4> Tente mudar as configurações acima <br> Sugiro alterar o campo [Palavras na frase] para 1 dessa forma você consegue analisar palavra a palavra! </h4>";
	}
	?>
	<?= "<br>Quantidade de correlações: ".$valida; ?>
</table>
<br>
<br>

<!--------------------------SOBRE--------------------------------------------------------->
<div>
	<h4>Sobre:</h4>
	<a href="https://github.com/carloscrls/modulares/blob/master/vetorizacaoDeTexto.php">Ver código fonte GITHUB</a>
	<br>
	<a href="https://educacao-executiva.fgv.br/rj/rio-de-janeiro/cursos/curta-media-duracao/curta-media-duracao-presencial/data-science-fundamentos?oferta=78270">Curso FGV que inspirou a criação do sistema</a>
	<br>
	<a href="https://educacao-executiva.fgv.br/rj/rio-de-janeiro/cursos/curta-media-duracao/curta-media-duracao-presencial/programacao-para-data-science?oferta=79177 ">Curso FGV que deu embasamento para criar a logica das funções</a>
</div>

<!--------------------------NUVEM DE PALAVRAS--------------------------------------------->
<?php

if ($gNuvem=="false") 
{
	die();
}
?>

<div align="center" id="cloud" name="cloud">
	<button onClick="history.go(0);">Refresh Page</button>
	<br><br><br>
	<!-- <button onClick="window.location.reload();">reload Page</button> -->

<?php

$maxPixel = 100;
foreach ($arrPalavraRepeticoes as $palavra => $repeticoes) 
	{
		if ((strlen($palavra)>=$minLetras) && ($repeticoes >= $minRepet)) 
		{
			$valida = 1;
			$maiorNum = empty($maiorNum)? $repeticoes:$maiorNum ;
			$pixeis = $repeticoes*$maxPixel/$maiorNum;
			$posicoes = array("horintal-tb","vertical-lr","horintal-tb");
			$colors=array("#07004D","#2D82B7","#42E2B8","#F3DFBF","#EB8A90","#0075F2","#00F2F2","#096B72");
			$color=$colors[array_rand($colors)];
			$posicao = $posicoes[array_rand($posicoes)];
			$br = array_rand($posicoes) <> 1?"<br>":"";
			 echo'<text 
					align="center"
					title="Repete: '.$repeticoes.' vezes"   
					style="background-color: white;
						   color:'.$color.' ;
						   font-size: '.$pixeis.'px; 
						   font-family: Impact;  
						   writing-mode: '.$posicao.';" 
					> '
					.$palavra.
				  ' </text>'.$br;
		}
	}
?>
</div>