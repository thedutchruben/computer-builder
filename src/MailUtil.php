<?php

namespace PcBuilder;


use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Send mails to customers / staff
 *
 * @version 1.0
 * @author Ruben de Roos
 */
class MailUtil
{

    /**
     * The mailer with the mail settings
     * @var Swift_Mailer
     */
    private Swift_Mailer $mailer;
    /**
     * The mail text and attributes
     * @var Swift_Message
     */
    private Swift_Message $message;

    /**
     * @param string $title The title of the mail
     * @param string $from The name / title the mail is from
     */
    public function __construct(string $title, string $from = "PcBuilder"){
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
     * Get the mail text and attributes
     * @return Swift_Message
     */
    public function getMessage(): Swift_Message
    {
        return $this->message;
    }

    /**
     * @param string $to the mail address of the receiver
     * @return void
     */
    public function send(string $to){
        $this->message->setTo($to);
        $this->mailer->send($this->message);
    }

}