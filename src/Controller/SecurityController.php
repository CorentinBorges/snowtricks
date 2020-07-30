<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Repository\TokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
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
     * @return Response
     * @Route("/signIn",name="app_signIn")
     */
    public function register(EntityManagerInterface $entityManager,Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer)
    {
        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userModel = $form->getData();


            $user = new User();

            $user
                ->setRoles(['ROLE_ADMIN'])
                ->setEmail($form['email']->getData())//TODO: demander à karim pourquoi setEmail($userModel['email'] ne fonctionne pas
                ->setUsername($form['username']->getData())
                ->setPassword($passwordEncoder->encodePassword($user,$form['password']->getData()))
                ->setIsValid(false);
            $entityManager->persist($user);

            $token = new Token();

            $now = new \DateTime();
            $time = new \DateTime();
            $expireTime = $time->modify('+1 hour');
            $token
                ->setName(uniqid() . uniqid())
                ->setUser($user)
                ->setCreatedAt($now)
                ->setExpiredAt($expireTime)
                ->setIsUsed(false);
            $entityManager->persist($token);
            $entityManager->flush();

            $email = (new Email())
                ->from('cb.corentinborges@gmail.com')
                ->to($form['email']->getData())
                ->subject("Snowtricks: confirmation par mail")
                ->text('Mail de confirmation')
                ->html('
                    <h1>Confirmation d\'inscription snowtricks</h1>
                    <p>Veuillez confirmer votre inscription en cliquant sur 
                    <a href="https://localhost:8000/confirmation/'.$token->getName().'"> Ce lien</a>
                    </p>');
            $mailer->send($email);

            $this->addFlash('registerSuccess',"Un mail de confirmation vous à été envoyé à l'adresse ".$form['email']->getData());
           return $this->redirectToRoute('app_homepage');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),

        ]);
    }

    /**
     * @Route("/confirmation/{name}",name="app_confirmUser")
     * @param Token $token
     * @return Response
     */
    public function confirmUser(Token $token,EntityManagerInterface $entityManager,Request $request)
    {
        if ($token->getIsUsed()) {
          throw new NotFoundHttpException();
        }

        $user = $token->getUser();
        $user->setIsValid(true);
        $token->setIsUsed(true);
        $entityManager->flush();



        return $this->render('security/confirmUser.html.twig');
    }
}
