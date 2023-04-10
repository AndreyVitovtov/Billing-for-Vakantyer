<?php

namespace Model;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail extends PHPMailer // https://petrenco.com/php.php?txt=717
{
    public function __construct()
    {
        parent::__construct(true);
        $this->settings();
    }

    private function settings()
    {
        $this->setLanguage(EMAIL_LANGUAGE, 'vendor/phpmailer/phpmailer/language/');
        $this->SMTPDebug = 1;
        $this->isSMTP();
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'tls';
        $this->Port = 587;
        $this->Host = EMAIL_HOST;
        $this->Username = EMAIL_USERNAME;
        $this->Password = EMAIL_PASSWORD;
        $this->isHTML();
    }

    /**
     * @throws Exception
     */
    public function sendMessage($email, $subject, $message): bool
    {
        try {
            $this->addAddress($email);
            $this->Subject = $subject;
            $this->Body = $message;
            $this->send();
            return true;
        } catch (Exception $ex) {
            $log = new Log(get_class());
            $log->setLog('Failed to send message', "email: " . $email . "\nsubject: " . $subject .
                "\nmessage: " . $message . "\nerror: " . $ex->getMessage());
            throw new Exception('Failed to send message ' . $ex->getMessage());
        }
    }
}