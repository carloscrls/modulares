<!DOCTYPE html>
<html lang="pt-br,pt,en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Minerador de textos</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
  <div id="index-banner" class="parallax-container z-depth-5">
    <div class="section no-pad-bot">
      <div class="container">
        <br>
        <br>
        <h1 class="header center blue-text text-lighten-2  z-depth-5">Text Mining</h1>
        <div class="row center">
          <h5 class="header col s12 light  z-depth-5">Sistema criado para extrair dados de textos</h5>
        </div>
        <div class="row center">
        </div>
        <br>
        <br>
      </div>
    </div>
    <div class="parallax"><img src="background1.jpg" alt="Unsplashed background img 1"></div>
  </div>

  <div class="container">
    <div class="section">
      <ul class="collapsible z-depth-5">
        <li>
          <div class="collapsible-header " >
            
            <i class="material-icons">textsms</i>
            Clique aqui para inserir o texto a ser trabalhado
          
          </div>
          <div class="collapsible-body">
            <!--   Icon Section   -->
            <div class="row">

              <?php
              include_once("Vetor.class.php");
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
                <b>TEXTO:</b>
                <br><textarea rows="4" cols="100" name="texto"><?= $texto ?></textarea>
                <br>
                <br><b>Palavras para compor uma frase =</b><input type="number" name="palavrasPorFrase" value="<?= $palavrasPorFrase ?>">
                  <label> OBS: 3 recomendado para textos grandes </label>
                <br>
                <br><b>Minimo de letras em uma palavra </b><input type="number" name="minLetras" value="<?= $minLetras ?>">
                  <label> OBS: 3 é o minimo recomendado </label>
                <br>
                <br><b>Minimo de repeticoes no texto >= </b><input type="number" name="minRepet" value="<?= $minRepet ?>">
                  <label> OBS: 2 é o minimo recomendado</label>
                <br>
                <br><b>Excluir palavras/preposições (Separar as palavras por virgula!):</b><br><textarea rows="4" cols="100" name="txtExcluir" placeholder="EX: AMOR,CAFE,CARRO"><?= $txtExcluir ?></textarea> 
                <br>
                <br><b>Manter somente essas palavras (Separar as palavras por virgula!):</b><br><textarea rows="4" cols="100" name="txtManter" placeholder="EX:RATO,REI,ROER"><?= $txtManter ?></textarea> 
                <br>
                <br>   
                <label>
                  <input type="checkbox" class="filled-in" name="excNum" value="checked" <?= $excNum ?> />
                  <span><b>Excluir números?</b></span>
                </label>

                <label>
                  <input type="checkbox" class="filled-in" name="gNuvem" value="checked" <?= $gNuvem ?> />
                  <span><b>Gerar nuvem?</b></span>
                </label>
                <br> 
                <br><input type="submit" class="btn" name="enviar">
              </form>
            </div>
          </div>
      </div>
    </div>
  </li>
</ul>

<?php
if ($gNuvem=="checked") 
{
?>
  <div class="parallax-container valign-wrapper z-depth-5 ">
    <div class="section no-pad-bot">
      <div class="container">
        <div class="row center">
          <!--------------------------NUVEM DE PALAVRAS--------------------------------------------->
          <div align="center" id="cloud" name="cloud">
          <button class="btn right" onClick="history.go(0);">Mudar nuvem</button>
          <br>
          <br>
          <br>
          <!-- <button onClick="window.location.reload();">reload Page</button> -->
          <?php
          $maxPixel = 50;
          $contador = 0;
          $comecaEm = count($arrPalavraRepeticoes)-10; // mostra só as 10 maiores palavras

          foreach ($arrPalavraRepeticoes as $palavra => $repeticoes) 
          {
            $contador ++;
            if ((strlen($palavra)>=$minLetras) && ($repeticoes >= $minRepet) && $contador>=$comecaEm) 
            {
              $valida = 1;
              $maiorNum = empty($maiorNum)? $repeticoes:$maiorNum ;
              $pixeis = $repeticoes*$maxPixel/$maiorNum;
              $posicoes = array("horintal-tb","","horintal-tb");
              $colors=array("#07004D","#2D82B7","#42E2B8","#F3DFBF","#EB8A90","#0075F2","#00F2F2","#096B72");
              $color=$colors[array_rand($colors)];
              $posicao = $posicoes[array_rand($posicoes)];
              $br = array_rand($posicoes) <> 1?"<br>":"";
               echo'<text 
                  class = "z-depth-5"
                  align="center"
                  title="Repete: '.$repeticoes.' vezes"   
                  style="background-color: #;
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
        </div>
      </div>
    </div>
    <div class="parallax"><img src="background2.jpg" alt="Unsplashed background img 2"></div>
  </div>
<?php
}
?>


  <!--------------------------------RESULTADO -------------------------------------------------->
  <?php  $resultadoLbl = $textoExemplo==$texto? "Resultado do <b>exemplo</b> acima <br><label>Altere o texto a ser trabalhado acima</label>": "Resultado" ?>
  <br>
  <div class="container z-depth-5">
    <div class="section">
      <div class="row">
        <div class="col s12 center">
          <h3><i class="mdi-content-send brown-text"></i></h3>
          <h4><?= $resultadoLbl  ?></h4>
          <p class="left-align light">
            <?= "<br>Quantidade de caracteres analisados: ".strlen($texto); ?>
            <?= "<br>Quantidade de palavras analisadas: ".count($arrText); ?>
            <br>
            <br>
            <table border="1" class="">
              <tr>
                <th>#</th>
                <th>Palavra/Frase</th>
                <th>Repetições</th>
              </tr>
              <?php
              $arrPalavraRepeticoes = array_reverse($arrPalavraRepeticoes);
              $valida=0;
              $totalRepeticoes=0;
              foreach ($arrPalavraRepeticoes as $palavra => $repeticoes) 
              {
                if ((strlen($palavra)>=$minLetras) && ($repeticoes >= $minRepet)) 
                {
                  $valida += 1;
                  $totalRepeticoes += $repeticoes;
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
              echo "<td>$totalRepeticoes</td>";
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
          </p>
        </div>
      </div>
    </div>
  </div>
  <br>
  <br>
  <div class="parallax-container valign-wrapper z-depth-5">
    <div class="section no-pad-bot">
      <div class="container">
        <div class="row center">
          <h5 class="header col s12 light">BIBLIOGRAFIA
            <div>
            <a class="btn" href="https://github.com/carloscrls/modulares/blob/master/vetorizacaoDeTexto.php">Ver código fonte GITHUB</a>
            <br>
            <a class="btn" href="https://educacao-executiva.fgv.br/rj/rio-de-janeiro/cursos/curta-media-duracao/curta-media-duracao-presencial/data-science-fundamentos?oferta=78270">Curso FGV que inspirou a criação do sistema</a>
            <br>
            <a class="btn" href="https://educacao-executiva.fgv.br/rj/rio-de-janeiro/cursos/curta-media-duracao/curta-media-duracao-presencial/programacao-para-data-science?oferta=79177 ">Curso FGV que deu embasamento para criar a logica das funções</a>
            </div>
          </h5>
        </div>
      </div>
    </div>
    <div class="parallax"><img src="background3.jpg" alt="Unsplashed background img 3"></div>
  </div>
  <footer class="page-footer blue">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Vetorizador de texto</h5>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Big Data</h5>
        </div>
        <div class="col l3 s12">
          <h5 class="white-text">Small Data</h5>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
      Made by <a class="brown-text text-lighten-3" href="https://github.com/carloscrls/modulares/blob/master/vetorizacaoDeTexto.php">Carlos Magno Silva Tavares</a>
      </div>
    </div>
  </footer>
  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  </body>
</html>