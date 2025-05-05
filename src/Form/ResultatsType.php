<?php

namespace App\Form;

use App\Entity\Resultats;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultatsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('competition', TextType::class, [
                'label' => 'Nom de la compétition',
            ])
            ->add('competiteur', TextType::class, [  // Nom du compétiteur
                'label' => 'Nom du compétiteur',
            ])
            ->add('resultat', TextType::class, [  // Changement pour "resultat" au lieu de "résultat"
                'label' => 'Résultat (ex: 1ère place, médaille d\'argent...)',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resultats::class,
        ]);
    }
}

