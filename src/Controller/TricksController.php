<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Message;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Repository\FigureRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{

    /**
     * @Route("/", name="app_homepage")
     */
    public function index(FigureRepository $figureRepository,Request $request)
    {
        $tricks = $figureRepository->findAll();
        return $this->render('tricks/index.html.twig', [
            'controller_name' => 'TricksController',
            'tricks' => $tricks,

        ]);
    }

    /**
     * @Route("/trick/{id}", name="app_show")
     */
    public function show(Figure $figure, Request $request, EntityManagerInterface $entityManager,$id,MessageRepository $messageRepository)
    {
        $form = $this->createForm(CommentFormType::class);
        $form->handleRequest($request);
        /** @var User $user */
        $user = $this->getUser();



        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Message  $comment  */
            $comment = $form->getData();
            $comment
                ->setFigure($figure)
                ->setUser($user)
                ->setCreatedAtNow();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success',"Votre commentaire à été ajouté");
            return $this->redirectToRoute('app_show',['id'=>$id]);
        }
        $nbMessage = $messageRepository->count($id);



        if (!empty($figure->getMessages())) {
            $reverseMessages = $messageRepository->reverseOrder($id);
        }
        else{
            $reverseMessages = null;
        }

        return $this->render("tricks/show.html.twig",[
            'trick'=> $figure,
            'messages'=>$reverseMessages,
            'nbMessages' => $nbMessage,
            'commentForm' => $form->createView(),


        ]);
    }

    /**
     * @Route("/messages/{id}",name="app_messages")
     */
    public function messages(Figure $figure,$id, MessageRepository $messageRepository)
    {
        if (!empty($figure->getMessages())) {
            $reverseMessages = $messageRepository->reverseOrder($id);
        }
        else{
            $reverseMessages = null;
        }

        return $this->render('tricks/comments.html.twig',[
            'messages'=>$reverseMessages
        ]);
    }
}
