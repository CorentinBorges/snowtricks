<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailSender
{


    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var Request
     */
    private $request;


    public function __construct(MailerInterface $mailer, Request $request)
    {
        $this->mailer = $mailer;
        $this->request = $request;
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
                    <a href="https://'.$this->request->server->get('HTTP_HOST').'/confirmation/'.$tokenName.'"> Ce lien</a>
                    </p> <br>
                    <strong>⚠️ Le lien n\'est actif que 5 jours</strong>');

        $this->mailer->send($email);
    }
}