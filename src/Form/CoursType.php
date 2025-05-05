<?php

// src/Form/CoursType.php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType; // Assure-toi d'utiliser ce type
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('jour', ChoiceType::class, [
            'choices' => [
                'Lundi' => 'Lundi',
                'Mardi' => 'Mardi',
                'Mercredi' => 'Mercredi',
                'Jeudi' => 'Jeudi',
                'Vendredi' => 'Vendredi',
                'Samedi' => 'Samedi',
                'Dimanche' => 'Dimanche',

               
            ],
        ])
            ->add('heureDebut', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure de début',
                'required' => true,
            ])
            ->add('heureFin', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure de fin',
                'required' => true,
            ])
            ->add('discipline', ChoiceType::class, [
                'choices' => [
                    'Judo' => 'Judo',
                    'Ju-jitsu' => 'Ju-jitsu',
                    'Taïso' => 'Taïso',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter le cours',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('color', ChoiceType::class, [
                'label' => 'Couleur de l\'événement',
                'choices' => [
                    'Bleu' => '#1E90FF',
                    'Vert' => '#32CD32',
                    'Orange' => '#FF8C00',
                    'Rouge' => '#FF4500',
                    'Violet' => '#9370DB',
                    'Gris' => '#808080',
                ],
                'choice_attr' => function($val, $key, $index) {
                    return ['style' => 'background-color:' . $val . '; color: white'];
                },
                'placeholder' => 'Choisissez une couleur',
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
