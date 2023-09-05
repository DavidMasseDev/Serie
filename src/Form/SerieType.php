<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Serie's name",
            ])
            ->add('overview', TextareaType::class, [
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    "Canceled" => "Canceled", // "Ce qui est affiché" => "La valeur de ce qui est affiché"
                    "Ended" => "Ended",
                    "Returning" => "Returning"
                ],
                // Permet l'affichage d'un select. Changer les -false- permet d'obtenir checkbox, radio, etc...
                "expanded" => false,
                "multiple" => false
            ])
            ->add('vote', IntegerType::class)
            ->add('popularity')
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    "Comedy" => "comedy",
                    "Thriller" => "thriller",
                    "Western" => "western",
                    "Drama" => "drama",
                    "Science-Fiction" => "SF"
                ],
                "expanded" => true,
                "multiple" => false
            ])
            ->add('firstAirDate', DateType::class, [
                'html5'=>true,
                'widget' => 'single_text'
            ])
            ->add('lastAirDate')
            ->add('backdrop')

            ->add('poster', FileType::class, [
                'mapped' => false, // l'entité ne doit pas le récupérer car c'est un fichier et non pas un string.
                'required' => false,
                'constraints' => [ // Ajout de contraintes de valdiation puisqu'on ne peut pas les faire dans l'entité
                    new Image([
                        'maxSize' => '1m',
                        'mimeTypesMessage' => 'Ceci n\'est pas une pipe.'
                    ])
                ]

            ])
            ->add('tmdbId');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
