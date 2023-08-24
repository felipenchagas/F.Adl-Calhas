<?php
// Inclui o arquivo class.phpmailer.php localizado na pasta class
require_once("novo/class.phpmailer.php");

/* error_reporting(E_ALL);    <-- DEBUG
ini_set("display_errors", 1); <-- DEBUG */

// Inicia a classe PHPMailer
$mail = new PHPMailer(true);
// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsSMTP(); // Define que a mensagem será SMTP

// Dados Formulário
    $nomeremetente = $_POST['nomeremetente'];
    $emailremetente = $_POST['emailremetente'];
    $cidade = $_POST['cidade'];
    $telefone = $_POST['telefone'];
    $ddd = $_POST['ddd'];
    $mensagem = $_POST['mensagem'];

function GerarOrcamento(){

    $arquivo = "orcamentos.txt";   
    $abrir = fopen($arquivo, 'r+') or die("O txt nao pode ser aberto.");   
    $contador = fread($abrir, filesize($arquivo));   
    $intcontador = (int) $contador;   
    $intcontador++;   
    rewind($abrir);   
    fwrite($abrir, $intcontador);   
    fclose($abrir);   
    return $intcontador;  

    }

    $orcamento = GerarOrcamento();

try {
	 /* $mail->SMTPDebug = 2; <-- DEBUG*/
	 $mail->CharSet = "utf8";
     $mail->IsSMTP(); /* Ativar SMTP*/
     $mail->Host = 'mail.adlcalhas.com.br'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
     $mail->SMTPAuth   = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
     $mail->Port       = 587; //  Usar 587 porta SMTP
     $mail->Username = 'orcamento@adlcalhas.com.br'; // Usuário do servidor SMTP (endereço de email)
     $mail->Password = 'orcamento9091#'; // Senha do servidor SMTP (senha do email usado)
$mail->AddAddress('orcamento@adlcalhas.com.br', 'ADL CALHAS');
     //Define o remetente
     // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=    
     //$mail->Addreply('orcamento@adlcalhas.com.br', 'Site'); // Seu e-mail
$mail->SetFrom('orcamento@adlcalhas.com.br', $emailremetente, $nomeremetente);
$mail->Subject = "ADL CALHAS - SITE - # $orcamento";//Assunto do e-mail	 
$mail->isHTML(true);
	
	
	
	
         
    //Campos abaixo são opcionais 
    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
    //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
    //$mail->AddAttachment('images/phpmailer.gif');      // Adicionar um anexo 
    //Define o corpo do email

    $message = file_get_contents('form.html'); 
       
    $message = str_replace('%orcamento%', $orcamento, $message); 
    $message = str_replace('%nome%',$nomeremetente, $message); 
    $message = str_replace('%telefone%', '('.$ddd.') '.$telefone, $message); 
    $message = str_replace('%cidade%', $cidade, $message);
    $message = str_replace('%email%', $emailremetente, $message); 
    $message = str_replace('%mensagem%', $mensagem, $message);  
	
    $mail->MsgHTML($message);  

    ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
    //$mail->MsgHTML(file_get_contents('arquivo.html'));
 
    $mail->Send();
    header("Location: sucesso.html");
 
    //caso apresente algum erro é apresentado abaixo com essa exceção.
    }

    catch (phpmailerException $e) {
        
        echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
}
?>