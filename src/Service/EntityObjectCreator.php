<?php


namespace App\Service;


use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Video;
use App\Form\TrickFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EntityObjectCreator
{
    /**
     * @param FormInterface $form
     * @param EntityManagerInterface $entityManager
     * @param bool $new
     * @return Figure
     */
    public function createOrEditFigure(FormInterface $form, EntityManagerInterface $entityManager,bool $new,$figure=null): Figure
    {
        if ($new) {
            $figure = new Figure();
        }

        $figure
            ->setName($form['name']->getData())
            ->setDescription($form['description']->getData())
            ->setGroupe($form['groupe']->getData());
        $new ? $figure->setCreatedAtNow() : $figure->setModifiedAtNow() ;
        $entityManager->persist($figure);
        return $figure;
    }


    public function createImage(UploadedFile $uploadedFile,$formImageName,$figure,EntityManagerInterface $entityManager)
    {
        //todo: setFirst
        $image = new Image();
        $imageName = uniqid() . $formImageName;
        $image->setName($imageName)
            ->setFigure($figure)
            ->setFirst('false');
        $entityManager->persist($image);
        $entityManager->flush();
        $uploadedFile->move('images', $imageName);

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