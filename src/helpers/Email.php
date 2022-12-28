<?php
namespace cadastroTarefas\helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Email{
    const HOST = 'smtp.gmail.com';
    const USER = 'muriloprivitera24@gmail.com';
    
    const SECURE = 'TLS';
    const PORT = 587;
    const CHARSET = 'UTF-8';
    const FROM_EMAIL = 'muriloprivitera24@gmail.com';
    const FROM_NAME = 'Murilo Privitera';

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
            $objMail->Host = self::HOST;
            $objMail->SMTPAuth = true;
            $objMail->Username = self::USER;
            $objMail->Password = self::PASS;
            $objMail->SMTPSecure = self::SECURE;
            $objMail->SMTPDebug  = 3;
            $objMail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $objMail->Port = self::PORT;
            $objMail->CharSet = self::CHARSET;
            $objMail->setFrom(self::FROM_EMAIL,self::FROM_NAME);

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

        } catch (Exception $e) {
            $this->erro = $e->getMessage();
            return false;
        }
    }
}