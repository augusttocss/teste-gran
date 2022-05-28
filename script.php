<?php
   $pdfText = $statusMsg = '';
   $status = 'error';
   
   // Se o formulario for enviado
   if(isset($_POST['submit'])) {
        // Se o arquivo for selecionado
        if(!empty($_FILES["pdf_file"]["name"])) {
            $fileName = basename($_FILES["pdf_files"]["tmp_name"]);
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        
            // Permitir certos formatos de arquivos
            $allowTypes = array('pdf');
            if(in_array($fileType, $allowTypes)) {
                //incluir arquivo autoload 
                include 'vendor/autoload.php';

                //Inicializando o arquivo pdf parser
                $parser = new \Smalot\PdfParser\Parser();

                // Procurando arquivo pdf para extrair texto
                $file = $_FILES["pdf_file"]["tmp_name"];

                // Analisando o arquivo pdf pelo analisador
                $pdf = $parser->parseFile($file);

                // Extraindo o texto do pdf 
                $text = $pdf->getText();

                // Adicionar linha para parar
                $pdfText = nl2br($text);

            }else {
                $statusMsg = '<p> Desculpe, o arquivo PDF teve um erro de carregamento</p>';
            }
        }else {
            $statusMsg ='<p>Por favor, Selecione outro arquivo pdf para extrair texto</P>';
        } 

   }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF EM TEXT</title>
</head>
<body>
    <div class="container">
        <div class="wrapper">
            <h2>Extrair texto de PDF</h2>
            <div class="cw-frm">
                <!-- Status da mensagem-->
                <?php if(!empty($statusMsg)) { ?>
                    <div class="status-mg <?php echo $status;?>"><?php echo $statusMsg;?></div>
                <?php } ?>

                <!-- Arquivo de formulario-->
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-input">
                        <label for="pdf_file"> PDF File</label>
                        <input type="file" name="pdf_file" placeholder="Selecione o arquivo pdf" required="">
                    </div>
                    <input type="submit" name="submit" class="btn" value="Extrair texto">
                </form>
            </div>
        </div>
        <div class="wrapper-res">
        <!--exibir texto extraído de upload pdf-->
        <?php if(!empty($pdfText)) { ?>
            <div class="frm-result">
                <p><?php echo $pdfText; ?> </p>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>