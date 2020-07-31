<?php

namespace App\Controller;

use App\Form\TrickFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminTricksController extends AbstractController
{
    /**
     * @Route("/admin/tricks/new", name="admin_tricks_new")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(TrickFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(isset($form['image'])){
                dump($form['image']->getData()->getClientOriginalName());

                /** @var  $file File */
                $file = $form['image']->getData();
                $file->move('images',$form['image']->getData()->getClientOriginalName());
            }

        }

        return $this->render('admin_tricks/new.html.twig', [
            'trickForm' => $form->createView(),

        ]);
    }
}
