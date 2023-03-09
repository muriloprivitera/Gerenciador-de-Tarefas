<?php
namespace cadastroTarefas\helpers;

use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;


class Email{

    private string $erro;

    public function retornaErro():string
    {
        return $this->erro;
    }

    public function enviaEmail(string|array $destinatarios, string $assunto, string $mensagem,string|array $anexos = [], string|array $ccs = [],string|array $bccs = []):bool
    {
        $this->erro = '';

        $objMail = new PHPMailer(true);
        try {
            $objMail->isSMTP(true);
            $objMail->Host = $_ENV['HOST'];
            $objMail->SMTPAuth = true;
            $objMail->Username = $_ENV['USER'];
            $objMail->Password = $_ENV['PASS'];
            $objMail->SMTPSecure = $_ENV['SECURE'];
            $objMail->SMTPDebug  = 3;
            $objMail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $objMail->Port = $_ENV['PORT'];
            $objMail->CharSet = $_ENV['CHARSET'];
            $objMail->setFrom($_ENV['FROM_EMAIL'],$_ENV['FROM_NAME']);

            $destinatarios = is_array($destinatarios)? $destinatarios :[$destinatarios];
            
            foreach ($destinatarios as $destinatario) {
                $objMail->addAddress($destinatario);
            }

            $anexos = is_array($anexos)? $anexos :[$anexos];

            foreach ($anexos as $anexo) {
                $objMail->addAttachment($anexo);
            }

            $ccs = is_array($ccs)? $ccs :[$ccs];

            foreach ($ccs as $cc) {
                $objMail->addCC($cc);
            }

            $bccs = is_array($bccs)? $bccs :[$bccs];

            foreach ($bccs as $bcc) {
                $objMail->addBCC($bcc);
            }

            $objMail->isHTML(true);
            $objMail->Subject = $assunto;
            $objMail->Body = $mensagem;

            return $objMail->send();

        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $this->erro = $e->getMessage();
            return false;
        }
    }
}