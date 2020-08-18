<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CheckUserEdit
{
    public $errors = [];


    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $tokenManager;


    public function __construct(UserRepository $userRepository, CsrfTokenManagerInterface $tokenManager)
    {
        $this->userRepository = $userRepository;
        $this->tokenManager = $tokenManager;
    }

    public function checkUser(User $user,$username,$email,$token)
    {
        $actualUsername = $user->getUsername();
        $actualEmail = $user->getEmail();
        $token = new CsrfToken('authenticate',$token);

        if ($this->userRepository->findOneBy(['username'=>$username])) {
            $otherUser = $this->userRepository->findOneBy(['username' => $username]);
            if (($actualUsername !== $otherUser->getUsername())) {
                $this->errors[]="Ce nom d'utilisateur est déjà utilisé";
            }
        }
        if (strlen($username)>=20) {
            $this->errors[]="Le nom d'utilisateur ne peut pas dépasser 20 caractères";
        }
        if (strlen($username)<=3) {
            $this->errors[]="Le nom d'utilisateur doit contenir au moins 3 caractères";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[]="Veuillez entrer un email valide";
        }
        if ($this->userRepository->findOneBy(['email'=>$email])) {
            $otherEmail = $this->userRepository->findOneBy(['email' => $email]);
            if (($actualEmail !== $otherEmail->getEmail())) {
                $this->errors[]="Cet email est déjà utilisé";
            }
        }

        if (!$this->tokenManager->isTokenValid($token)) {

            throw new InvalidCsrfTokenException();
        }

        return $this->errors;
    }

    public function isValid(User $user,$username,$email,$token)
    {
        $this->checkUser($user,$username,$email,$token);
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}