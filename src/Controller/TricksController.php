<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Repository\FigureRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{

    /**
     * @Route("/", name="app_homepage")
     * @param FigureRepository $figureRepository
     * @return Response
     */
    public function index(FigureRepository $figureRepository)
    {
        $tricks = $figureRepository->findAll();
        return $this->render('tricks/index.html.twig', [
            'controller_name' => 'TricksController',
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/trick/{id}", name="app_show")
     * @param Figure $figure
     * @param Request $request
     * @param $id
     * @param MessageRepository $messageRepository
     * @return RedirectResponse|Response
     */
    public function show(Figure $figure, Request $request, $id, MessageRepository $messageRepository)
    {
        $form = $this->createForm(CommentFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            /** @var Message  $comment  */
            $comment = $form->getData();
            $messageRepository->addMessageDatabase($comment, $figure, $user);
            $this->addFlash('success', "Votre commentaire à été ajouté");
            return $this->redirectToRoute('app_show', ['id' => $id]);
        }

        $nbMessage = $messageRepository->count($id);
        if (!empty($figure->getMessages())) {
            $reverseMessages = $messageRepository->reverseOrder($id);
        } else {
            $reverseMessages = null;
        }

        return $this->render("tricks/show.html.twig", [
            'trick' => $figure,
            'messages' => $reverseMessages,
            'nbMessages' => $nbMessage,
            'commentForm' => $form->createView(),
        ]);
    }
}
