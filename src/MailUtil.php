<?php

namespace PcBuilder;


use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class MailUtil
{

    private Swift_Mailer $mailer;
    private Swift_Message $message;


    /**
     */
    public function __construct($title,$from){
        $transport = (new Swift_SmtpTransport($_ENV['EMAIL_HOST'], 25))
            ->setUsername($_ENV['EMAIL_NAME'])
            ->setPassword($_ENV['EMAIL_PASSWORD'])
        ;

        $this->mailer = new Swift_Mailer($transport);

        $this->message = (new Swift_Message($title))
            ->setFrom([$_ENV['EMAIL_NAME'] => $from]);
        ;
    }

    /**
     * @return Swift_Message
     */
    public function getMessage(): Swift_Message
    {
        return $this->message;
    }

    /**
     */
    public function send($to){
        $this->message->setTo($to);
        $this->mailer->send($this->message);
    }

}