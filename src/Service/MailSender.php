<?php


namespace App\Service;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailSender
{


    /**
     * @var MailerInterface
     */
    private $mailer;



    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;

    }

    public function sendConfirmationMail(string $from, string $to,string $tokenName)
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject("Snowtricks: Confirmation par mail")
            ->text('Mail de confirmation')
            ->html('
                    <h1>Confirmation d\'inscription snowtricks</h1>
                    <p>Veuillez confirmer votre inscription en cliquant sur 
                    <a href="https://localhost:8000/confirmation/'.$tokenName.'"> Ce lien</a>
                    </p> <br>
                    <strong>⚠️ Le lien n\'est actif que 5 jours</strong>');

        $this->mailer->send($email);
    }
}