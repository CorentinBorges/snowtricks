<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Image;
use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(FigureRepository $figureRepository)
    {
        $tricks = $figureRepository->findAll();
        return $this->render('tricks/index.html.twig', [
            'controller_name' => 'TricksController',
            'tricks'=>$tricks
        ]);
    }

    /**
     * @Route("/trick/{id}", name="app_show")
     */
    public function show(Figure $figure)
    {
        return $this->render("tricks/show.html.twig",[
            'figures'=> $figure
        ]);
    }
}
