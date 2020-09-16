<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\ResetPassFormType;
use App\Form\UserRegistrationFormType;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Service\MailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UserPasswordEncoder $passwordEncoder
     * @param MailerInterface $mailer
     * @param TokenRepository $tokenRepository
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/signIn",name="app_signIn")
     */
    public function register(EntityManagerInterface $entityManager,Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer,TokenRepository $tokenRepository,UserRepository $userRepository)
    {
        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $userRepository->createUser($user, 'ROLE_ADMIN', $passwordEncoder, $form);
//            $user
//                ->setRoles(['ROLE_ADMIN'])
//                ->setPassword($passwordEncoder->encodePassword($user,$form['password']->getData()))
//                ->setIsValid(false);
//            $entityManager->persist($user);
            $entityManager->persist($user);
            $token = new Token();
            $tokenRepository->createTokenInDatabase($user,$token);

            $mailSender = new MailSender($mailer,$request);
            $mailSender->sendMail($form['email']->getData(),$token->getName(),MailSender::CONFIRM_SIGN_IN);

            $this->addFlash('success',"Un mail de confirmation vous à été envoyé à l'adresse ".$form['email']->getData());

           return $this->redirectToRoute('app_homepage');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),

        ]);
    }

    /**
     * @Route("/confirmation/{name}",name="app_confirmUser")
     * @param Token $token
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function confirmUser(Token $token,EntityManagerInterface $entityManager,Request $request)
    {
        $now = new \DateTime("now");
        if ($token->getIsUsed() || $token->getExpiredAt()<$now) {
          throw new NotFoundHttpException();
        }

        $user = $token->getUser();
        $user->setIsValid(true);
        $token->setIsUsed(true);
        $entityManager->flush();

        return $this->render('security/confirmUser.html.twig');
    }

    /**
     * @Route("/resetPass",name="app_reset_pass")
     */
    public function forgotPassword(Request $request, UserRepository $userRepository,TokenRepository $tokenRepository,MailerInterface $mailer)
    {
            if ($request->request->has("username")) {
                $username=$request->request->get("username");
                if (!$userRepository->findOneBy(['username' => $username])){
                    $this->addFlash("error","Cet utilisateur n'existe pas");
                }
                else{
                    $user = $userRepository->findOneBy(['username' => $username]);
                    $userMail = $user->getEmail();

                    $token = new Token();
                    $tokenRepository->createTokenInDatabase($user,$token);

                    $mailSender = new MailSender($mailer,$request);
                    $mailSender->sendMail($userMail,$token->getName(),MailSender::RESET_PASS);
                    return $this->redirectToRoute('app_confirm_send_pass');
                }
            }

        return $this->render('security/resetPass.html.twig');
    }

    /**
     * @Route("/confirmationPassEnvoyé",name="app_confirm_send_pass")
     */
    public function confirmationMailPassSend()
    {
        return $this->render("security/confirmSendPass.html.twig");
    }

    /**
     * @Route("/newPass/{name}")
     */
    public function changePass(Token $token,Request $request,UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $entityManager)
    {
        $now = new \DateTime("now");
        if ($token->getIsUsed() || $token->getExpiredAt()<$now = new \DateTime("now")) {
            throw new NotFoundHttpException("La page que vous demandez n'existe pas");
        }

        $form = $this->createForm(ResetPassFormType::class);
        $form->handleRequest($request);
        $user = $token->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form['email']->getData()==$user->getEmail()) {
                $token->setIsUsed(true);
                $entityManager->persist($token);
                $user->setPassword($passwordEncoder->encodePassword($user, $form['password']->getData()));
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash("success","Votre mot de passe à été modifié!");
                return $this->redirectToRoute('app_homepage');
            } else {
                $form->get('email')->addError(new FormError("Le mail indiqué est incorrect"));
            }

        }

        return $this->render('security/changePass.html.twig',[
            'passForm'=>$form->createView(),
        ]);
    }
}
