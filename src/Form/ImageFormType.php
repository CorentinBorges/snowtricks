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

class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ($options['is_edit']) {
            $builder
                ->add('id',HiddenType::class,[
                ])
                ->add('name',HiddenType::class,[
                ])
                ->add('image',FileType::class,[
                    'data_class' => Image::class,
                    'label'=>'Nouvelle image ( Laisser ce champ vide pour garder l\'image)',
                    'required'=>false,
                    'mapped'=>false,
                    'attr'=>[
                        'accept' => "image/png, image/jpeg",
                        'custom-file-label' => 'charger',
                    ],
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
        }

        else{
            $builder
                ->add('image',FileType::class,[
                    'data_class' => Image::class,
                    'label'=>'Nouvelle image',
                    'required'=>true,
                    'mapped'=>false,
                    'attr'=>[
                        'accept' => "image/png, image/jpeg",
                        'custom-file-label' => 'charger',
                    ],
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
        }

        $builder
            ->add('alt',TextType::class,[
                'label'=>"Champs alt"
                ]
            );

        if (!$options['is_user_edit']) {
            $builder->add('first', CheckboxType::class,[
                'attr'=>[
                    'class'=>'checkFirst mt-2',
                    'type'=>"radio",
                    'checked'=>false
                ],
                'label'=>'Image mise en avant',
                'required'=>false,

            ]);
        }

        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
            'data_class' => Image::class,
            'is_edit' => false,
            'is_user_edit' => false,
        ]);
        $resolver->setAllowedTypes('is_edit', "bool");
        $resolver->setAllowedTypes('is_user_edit', "bool");
    }
}
