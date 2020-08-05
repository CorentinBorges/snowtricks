<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Unique;

class ChangeUserDataFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class,[
                'label'=>'Nom d\'utilisateur'
            ])
            ->add('email',EmailType::class,[
                'constraints'=>[
                    new Unique()]
            ])
            ->add('image',FileType::class,[
            'data_class' => Image::class,

                        'label'=>'Changer d\'avatar',
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
                    ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
