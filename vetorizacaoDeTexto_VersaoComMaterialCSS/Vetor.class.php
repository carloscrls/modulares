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