<?php

namespace App\Form;

use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

class TrickFormType extends AbstractType
{
    const NB_IMAGE = 4;
    const NB_VIDEO = 3;
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hideFieldTypeImage = $options['is_edit'] ? HiddenType::class : FileType::class;
        $hideFieldTypeVideo = $options['is_edit'] ? HiddenType::class : TextType::class;
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du trick *',
                'constraints' => [
                    new NotBlank([
                            'message' => 'Vous devez indiquer un nom de figure'
                        ]
                    )],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description *',
                'constraints' => [
                    new NotBlank([
                            'message' => 'Vous devez ajouter une description'
                        ]
                    )],
            ])
            ->add('groupe', ChoiceType::class, [
                'label' => 'Groupe *',
                'choices' => [
                    'grabs' => 'grabs',
                    'rotations' => 'rotations',
                    'flips' => 'flips',
                    'slides' => 'slides',
                    'old school' => 'old school'
                ]
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true
            ]);

            /*for ($i=1;$i<=self::NB_IMAGE;$i++) {

                $textFirst = ($i == 1) ? " (image mise en avant)" : "";

                $builder
                    ->add('image'.$i,$hideFieldTypeImage,[
                        'data_class' => Image::class,

                        'label'=>'Image '.$i.$textFirst,
                        'mapped'=>false,
                        'attr'=>[
                            'accept' => "image/png, image/jpeg",
                            'custom-file-label' => 'charger'],
                        'required'=>false,
                        'constraints'=>[
                            new File([
                                'maxSize' => '200M',
                                'mimeTypes'=>[
                                    'image/jpeg',
                                    'image/png'
                                ],
                                'mimeTypesMessage' => 'Le fichier doit être de type jpeg ou png'
                            ])
                        ]
                    ]);
            }*/

        for ($n=1;$n<=self::NB_VIDEO; $n++) {
            $builder
                ->add('video'.$n,$hideFieldTypeVideo,[
                    'label'=>'Vidéo '.$n.' (Url Youtube)',
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


    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
            'is_edit' => false,
        ]);

        $resolver->setAllowedTypes('is_edit', "bool");
    }
}
