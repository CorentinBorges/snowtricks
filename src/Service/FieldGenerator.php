<?php


namespace App\Service;


use App\Entity\Figure;
use App\Entity\Image;
use App\Form\TrickFormType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

class FieldGenerator
{

    public function addImageField(FormInterface $form,string $fieldName,$fieldLabel,Image $image=null)
    {
        $form->add($fieldName,FileType::class,[
            'data_class' => Image::class,
            'data' => $image,
            'label'=>$fieldLabel,
            'mapped'=>false,
            'attr'=>[
                'accept' => "image/png, image/jpeg",
                'custom-file-label' => 'charger',
                'placeholder'=>$image->getName()],
            'required'=>false,
            'constraints'=>[
                new \Symfony\Component\Validator\Constraints\File([
                    'maxSize' => '200M',
                    'mimeTypes'=>[
                        'image/jpeg',
                        'image/png'
                    ],
                    'mimeTypesMessage' => 'Le fichier doit être de type jpeg ou png'
                ])
            ]
        ]);
    }

    public function addImageFields(Figure $figure,FormInterface $form)
    {
        $images = [];
        $j = 1;
        foreach ($figure->getImages() as $image) {
            $images["image" . $j] = $image;
            $j++;
        }

        for ($i=1;$i<=TrickFormType::NB_IMAGE;$i++) {
            if (isset($images["image" . $i])) {
                $this->addImageField($form,"image".$i,"Image ".$i,$images["image".$i]);
            }
        }
    }

    public function addVideoField(FormInterface $form,$fieldName,$fieldLabel,$value=null)
    {
            $form
                ->add($fieldName,TextType::class,[
                    'label'=>$fieldLabel.' (Url Youtube)',
                    'data' =>$value,
                    'required'=>false,
                    'mapped'=>false,
                    'constraints'=>[
                        new Url([
                            'message'=>"veuillez rentrer un url valide"
                        ]),
                        new Regex([
                            'pattern'=>'#^https://youtu.be/#',
                            'message'=>'Veuillez coller un lien youtube avec : click droit->"Copier l\'URL de la video"'
                        ])
                    ]
                ]);
        }

    public function addVideoFields(Figure $figure,FormInterface $form)
    {
        $videos = [];
        $j = 1;
        foreach ($figure->getVideos() as $video) {
            $linkArray=preg_split('#/#',$video->getLink());
            $linkCode = $linkArray[3];
            $videos[$j] = 'https://youtu.be/' . $linkCode;
            $j++;
        }

        for ($i=1;$i<=TrickFormType::NB_IMAGE;$i++) {
            if (isset($videos[$i])) {
                $this->addVideoField($form,"video".$i,"Video ".$i." (Url youtube)",$videos[$i]);
            }
        }
    }
}