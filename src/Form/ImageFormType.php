<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image',FileType::class,[
                'data_class' => Image::class,
                'label'=>'Nouvelle image',
                'required'=>true,
                'mapped'=>false,
                'attr'=>[
                    'accept' => "image/png, image/jpeg",
                    'custom-file-label' => 'charger'],
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
            ])
            ->add('alt',TextType::class,[
                'label'=>"Champs alt"
                ]
            )
            ->add('first', CheckboxType::class,[
                'attr'=>[
                    'class'=>'checkFirst'
                ],
                'required'=>false,
            ])
            ->add('idImage',HiddenType::class,[
                'mapped'=>false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
