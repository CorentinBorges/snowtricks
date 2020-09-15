<?php


namespace App\Service;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailSender
{
    const FROM = 'cb.corentinborges@gmail.com';
    const CONFIRM_SIGN_IN=0;
    const RESET_PASS = 1;



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


    public function sendMail(string $to,string $tokenName,int $messageType)
    {
        if ($messageType==self::CONFIRM_SIGN_IN) {
            $message = '
                    <h1>Confirmation d\'inscription snowtricks</h1>
                    <p>Veuillez confirmer votre inscription en cliquant sur 
                    <a href="https://' . $this->request->server->get('HTTP_HOST') . '/confirmation/' . $tokenName . '"> Ce lien</a>
                    </p> <br>
                    <strong>⚠️ Le lien n\'est actif que 2 jours</strong>';
            $subject = "Snowtricks: Confirmation d'inscription";
        }
        else if ($messageType===self::RESET_PASS) {
            $message = '
                    <h1>Renouveler son mot de passe snowtricks</h1>
                    <p>Pour réinitialiser votre mot de passe, veuillez cliquer sur 
                    <a href="https://' . $this->request->server->get('HTTP_HOST') . '/newPass/' . $tokenName . '"> Ce lien</a>
                    </p> <br>
                    <strong>⚠️ Le lien n\'est actif que 2 jours</strong>';
            $subject = "Snowtricks: Réinitialisation du mot de passe";

        }
        else{
            throw new \InvalidArgumentException("Message type has to be one of the MailSender constante");
        }

    $email = (new Email())
            ->from(self::FROM)
            ->to($to)
            ->subject($subject)
            ->text('Mail de confirmation')
            ->html($message);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new Exception("Echec de l'envoie");
        }
    }
}