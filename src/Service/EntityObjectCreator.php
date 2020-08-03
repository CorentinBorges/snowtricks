<?php


namespace App\Service;


use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Video;
use App\Form\TrickFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;

class EntityObjectCreator
{
    /**
     * @param FormInterface $form
     * @param EntityManagerInterface $entityManager
     * @return Figure
     */
    public function createFigure(FormInterface $form, EntityManagerInterface $entityManager): Figure
    {
        $figure = new Figure();
        $figure
            ->setName($form['name']->getData())
            ->setDescription($form['description']->getData())
            ->setGroupe($form['groupe']->getData())
            ->setCreatedAtNow();
        $entityManager->persist($figure);
        return $figure;
    }

    public function createImages(FormInterface $form, Figure $figure, EntityManagerInterface $entityManager)
    {
        for ($i=1;$i<=TrickFormType::NB_IMAGE;$i++) {
            if (isset($form['image' . $i]) && !empty($form['image' . $i]->getData())) {
                $image = new Image();
                $imageName = uniqid() . $form['image' . $i]->getData()->getClientOriginalName();
                $image
                    ->setName($imageName)
                    ->setFigure($figure)
                    ->setFirst($i == 1);

                $entityManager->persist($image);
                /** @var  $file File */
                $file = $form['image' . $i]->getData();
                $file->move('images', $imageName);
            }
        }
    }

    public function createVideos(FormInterface $form, Figure $figure,EntityManagerInterface $entityManager)
    {
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
    }


}