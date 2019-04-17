<?php

class Mail
{
    /**
     * Mail sender with PHPMailer library
     *
     * @param string $to: target e-mail address
     * @param string $sender: sender name
     * @param string $sender_mail: e-mail address
     * @param string $subject: e-mail subject or title
     * @param string $content: e-mail content (pain text or html content)
     * @param array $configs: PHPMailer Configs
     * @return bool
     */
    public function mailerSend($to, $sender, $subject, $content, $configs)
    {
        $functions = new Functions();

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = $configs['mail_smptp_debug'];
            $mail->isSMTP();
            $mail->Host = $configs['mail_host'];
            $mail->SMTPAuth = $configs['mail_smtp_auth'] == '1' ? true : false;
            $mail->Username = $configs['mail_username'];
            $mail->Password = $configs['mail_password'];
            $mail->SMTPSecure = $configs['mail_smtp_secure'];
            $mail->Port = $configs['mail_port'];
            $mail->SMTPAutoTLS = $configs['mail_smtp_auto_tls'] == '1' ? true : false;

            //Recipients
            $mail->setFrom($configs['mail_from'], $sender);
            $mail->addAddress($to);

            // content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $content;
            $mail->AltBody = $content;

            return $mail->send();
        } catch (Exception $error) {
            return [
                'error' => $error,
            ];
        }
    }

    /**
     * Custom mail sender with native mail function
     *
     * @param string $to: target e-mail address
     * @param string $sender: sender name
     * @param string $sender_mail: e-mail address
     * @param string $subject: e-mail subject or title
     * @param string $content: e-mail content (pain text or html content)
     * @return bool
     */
    public function mailSend($to, $sender, $sender_mail, $subject, $content)
    {
        // mail hader
        $headers = "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=UTF-8\n";
        $headers .= "X-Mailer: PHP\n";
        $headers .= "X-Sender: PHP\n";
        $headers .= "From: $sender<{$sender_mail}>\n";
        $headers .= "Reply-To: $sender<{$sender_mail}>\n";
        $headers .= "Return-Path: $sender<{$sender_mail}>\n";

        // send mail
        return mail($to, $subject, $content, $headers);
    }
}
