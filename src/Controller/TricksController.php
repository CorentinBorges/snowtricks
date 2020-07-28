<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index()
    {
        return $this->render('tricks/index.html.twig', [
            'controller_name' => 'TricksController',
        ]);
    }

    /**
     * @Route("/trick/{id}", name="app_show")
     */
    public function show(Image $figure)
    {
        return $this->render("tricks/show.html.twig");
    }
}
