<?php

namespace App\Form;

use App\Entity\Inscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionFilterType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*// Sélection du grade minimal
            ->add('gradeMin', ChoiceType::class, [
                'choices' => [
                    'Blanc' => 'Blanc',
                    'Jaune' => 'Jaune',
                    'Orange' => 'Orange',
                    'Verte' => 'Verte',
                    'Bleue' => 'Bleue',
                    'Marron' => 'Marron',
                    'Noire' => 'Noire',
                ],
                'required' => false,
                'placeholder' => 'Sélectionnez un grade min',
            ])
            // Sélection du grade maximal
            ->add('gradeMax', ChoiceType::class, [
                'choices' => [
                    'Blanc' => 'Blanc',
                    'Jaune' => 'Jaune',
                    'Orange' => 'Orange',
                    'Verte' => 'Verte',
                    'Bleue' => 'Bleue',
                    'Marron' => 'Marron',
                    'Noire' => 'Noire',
                ],
                'required' => false,
                'placeholder' => 'Sélectionnez un grade max',
            ]) */
            // Filtre par date de naissance minimale
            ->add('dateNaissanceMin', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de naissance minimale',
            ])
            // Filtre par date de naissance maximale
            ->add('dateNaissanceMax', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de naissance maximale',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Ne pas lier le formulaire à l'entité Inscription ici
        // Pas de data_class pour le formulaire de filtrage
        $resolver->setDefaults([
            // On n'associe pas data_class ici car ces filtres ne font pas partie de l'entité Inscription
        ]);
    }
}


