<?php
// src/Form/EvenementType.php
namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;



class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('titre', TextType::class)
        ->add('description', TextType::class)
        ->add('start', DateTimeType::class)
        ->add('end', DateTimeType::class)
        ->add('discipline', ChoiceType::class, [
            'choices' => [
                'Judo' => 'Judo',
                'Ju-jitsu' => 'Ju-jitsu',
                'Taïso' => 'Taïso',
            ],
        ])
        ->add('prospectus', FileType::class, [
            'label' => 'Prospectus PDF (optionnel)',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'application/pdf',
                    ],
                    'mimeTypesMessage' => 'Merci de télécharger un fichier PDF valide',
                ])
            ],
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
            'data_class' => Evenement::class,
        ]);
    }
}
