<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminTricksController extends AbstractController
{
    /**
     * @Route("/admin/tricks/new", name="admin_tricks_new")
     */
    public function index()
    {

        return $this->render('admin_tricks/new.html.twig', [

        ]);
    }
}
