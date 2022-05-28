<?php

// INCLUIR O AUTOLOADER DO COMPOSER
include  'vendor/autoload.php' ;

// CHAMANDO O DOCUMENTO PDF 
$file = 'resultado.PDF';

// ANALISA O ARQUIVO PDF E CRIA OS OBJETOS NECESSARIOS
$parser = new \Smalot\PdfParser\Parser();
$pdf = $parser->parseFile($file);

// PEGA TODAS AS PAGINAS DO ARQUIVO PDF
$text = $pdf->getText();

// SEPARA AS LISTAS DOS CANDIDATOS
$list = explode("1.1.1", $text); 

//CRIA ARQUIVO
$fp =  fopen("Lista_concurso.csv", "w");
fclose($fp);

function criarList($filePdf, $ac) {

  
    // REMOVENDO NÚMEROS DE PÁGINAS
    $filePdf = preg_replace('/\s\d\b/',"",$filePdf);

    // REMOVENDO QUEBRA DE LINHA
    $filePdf = preg_replace('/\n/'," ",$filePdf);
    
    // SEPARANDO LISTA 
    preg_match_all('/\d+\,.+\w+\,.+\d+\.\d+(\/|\.)/', $filePdf, $sep);

    // SEPARANDO CANDIDATOS
    $candidates = explode("/",$sep[0][0]);
    
    $dataOutput = "Inscrição, Nome do Candidato, Número de acertos, Nota provisório";
	$fp = fopen('Lista_concurso.csv', 'a+');
   
    if($ac == 'AC') {
        $dataOutput.="\n";
    }
    if($ac == 'PNE') {
        $dataOutput .=",PNE\n";
    }
    fwrite($fp, $dataOutput);

    foreach($candidates as $candidate) {
        
        if($candidate[-1] == ".") {
            $candidate = substr_replace($candidate,"", -1);
        }
        
        if($ac == 'AC') {
            $candidate .="\n";
        }
        if($ac == 'PNE') {
            $candidate .=", SIM\n";
        }

		fwrite($fp, $candidate);
    }
	fclose($fp);
}


criarList($list[0],'AC');
criarList($list[1],'PNE');

$file = "Lista_concurso.csv"; 

header( "Content-Type: application/force-download");
header( "Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header( "Content-disposition: attachment; filename=aprovados.csv");
header( "Pragma: no-cache");
header( "Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header( "Expires: 0");
header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=Customers_Export.csv');
echo "\xEF\xBB\xBF";
readfile( $file);

?>






