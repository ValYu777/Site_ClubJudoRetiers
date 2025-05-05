<?php

namespace App\Form;

use App\Entity\Inscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('numLicence', TextType::class, [
                'label' => 'Numéro de licence'
            ])
            ->add('grade', ChoiceType::class, [
                'label' => 'Grade',
                'choices'  => [
                    'Blanche' => 'Blanche',
                    'Jaune' => 'Jaune',
                    'Orange' => 'Orange',
                    'Verte' => 'Verte',
                    'Bleue' => 'Bleue',
                    'Marron' => 'Marron',
                    'Noire' => 'Noire',
                ],
                'expanded' => false,  // Afficher un menu déroulant (pas des cases à cocher)
                'multiple' => false, // Une seule option sélectionnée
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse postale'
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance'
            ])
            ->add('tel', TextType::class, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
        ]);
    }
}
