<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFirst',FileType::class,[
                'label'=>'Image mise en avant (facultative)',
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
            ->add('image',FileType::class,[
                'label'=>'Image (facultative)',
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
            ->add('title',TextType::class,[
                'label' => 'Nom du trick',
                'constraints'=>[
                    new NotBlank([
                        'message'=>'Vous devez indiquer un nom de figure'
                        ]
                    )],
            ])
            ->add('content',TextareaType::class,[
                'constraints'=>[
                    new NotBlank([
                            'message'=>'Vous devez ajouter une description'
                        ]
                    )],
            ])
            ->add('groupe', ChoiceType::class,[
                'choices'=>[
                    'grabs'=>'grabs',
                    'rotations'=>'rotations',
                    'flips'=>'flips',
                    'slides'=>'slides',
                    'old school' => 'old school'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
