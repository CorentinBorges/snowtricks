<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Video;
use App\Form\TrickFormType;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(TrickFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $figure = new Figure();
            $figure
                ->setName($form['name']->getData())
                ->setDescription($form['description']->getData())
                ->setGroupe($form['groupe']->getData())
                ->setCreatedAtNow();
            $entityManager->persist($figure);


            for ($i=1;$i<=TrickFormType::NB_IMAGE;$i++) {
                if (isset($form['image'.$i]) && !empty($form['image'.$i]->getData())) {
                    $image = new Image();

                    $imageName = uniqid().$form['image'.$i]->getData()->getClientOriginalName();
                    $image
                        ->setName($imageName)
                        ->setFigure($figure)
                        ->setFirst($i == 1);

                    $entityManager->persist($image);

                    ;
                    /** @var  $file File */
                    $file = $form['image'.$i]->getData();
                    $file->move('images',$imageName);


                }
            }

            for ($n=1;$n<=TrickFormType::NB_VIDEO;$n++) {
                if (isset($form['video'.$n]) &&  !empty($form['video'.$n]->getData())) {
                    $video = new Video();
                    $linkArray=preg_split('#/#',$form['video'.$n]->getData());
                    $linkCode = $linkArray[3];
                    $video
                        ->setFigure($figure)
                        ->setLink('https://www.youtube.com/embed/'.$linkCode);

                    $entityManager->persist($video);
                }

            }
            $entityManager->flush();
            $this->addFlash("success","Yes!!! Votre trick à bien été ajouté!! ❄❄❄");
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('admin_tricks/new.html.twig', [
            'trickForm' => $form->createView(),

        ]);
    }
}
