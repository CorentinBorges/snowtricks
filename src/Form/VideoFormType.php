<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;
use function Sodium\add;

class VideoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('link',TextType::class,[
                'label'=>'Vidéo (Url Youtube)',
                'required'=> true,
                'constraints'=>[
                    new Url([
                        'message'=>"veuillez rentrer un url valide"
                    ]),
                    new Regex([
                        'pattern'=>'#^https://youtu.be/#',
                        'message'=>'Veuillez coller un lien youtube avec : clique droit->"Copier l\'URL de la video"'
                    ])
                ]
            ])

        ->add('id',HiddenType::class,[
            'mapped' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
