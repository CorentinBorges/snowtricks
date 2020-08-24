<?php

namespace App\Form;

use App\Entity\Figure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickFormType extends AbstractType
{
    const NB_IMAGE = 4;
    const NB_VIDEO = 3;
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

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
            ]);
        if ($options['is_edit']) {
           $builder ->add('images', CollectionType::class, [
                'entry_type' => ImageFormType::class,
                'entry_options' => ['label' => false],
                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true]);
            $builder->add('videos',CollectionType::class,[
                'entry_type'=>VideoFormType::class,
                'mapped' => false,
                'allow_add'=>true,
                'allow_delete'=>true,
                'prototype'=>true
            ]);
        }
        else{
            $builder
                ->add('images', CollectionType::class, [
                    'entry_type' => ImageFormType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true]);
            $builder->add('videos',CollectionType::class,[
                'entry_type'=>VideoFormType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'prototype'=>true
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
