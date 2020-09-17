<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPassFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Mot de passe',
                'constraints' => [
                    new Length([
                        "min" => UserRegistrationFormType::PASS_MIN,
                        'minMessage' => "Le mot de passe doit contenir au moins 8 caractères",
                        "max" => UserRegistrationFormType::PASS_MAX,
                        "maxMessage" => "Le mot de passe ne peut pas contenir plus de 50 caractères"
                    ]),

                    new Regex([
                        'pattern' => '#[0-9]+#',
                        'message' => "Le mot de passe doit contenir au moins un chiffre "
                    ]),

                    new Regex([
                        'pattern' => '#[a-z]+#',
                        'message' => "Le mot de passe doit contenir au moins une lettre minuscule"
                    ]),

                    new Regex([
                        'pattern' => '#[A-Z]+#',
                        'message' => "Le mot de passe doit contenir au moins une lettre majuscule"
                    ]),

                    new NotBlank([
                        'message' => "Le champ mot de passe ne peut pas être vide"
                    ])

                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
