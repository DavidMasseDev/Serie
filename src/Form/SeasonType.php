<?php

namespace App\Form;

use App\Entity\Season;
use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', IntegerType::class, [

            ])
            ->add('firstAirDate', DateType::class, [
                'html5' => true,
                'widget' => "single_text",
            ])
            ->add('overview', TextareaType::class, [

            ])
            ->add('poster', TextType::class, [

            ])
            ->add('tmdbId', IntegerType::class, [

            ])
            ->add('Serie', EntityType::class, [
                'class' => Serie::class,
                'choice_label' => "name", // Inutile si on a une méthode toString { $this->getName()}
                'query_builder' => function(SerieRepository $serieRepository){
                    return $serieRepository->createQueryBuilder('s')->addOrderBy("s.name", "ASC")->andWhere("s.status = 'returning'"); // equivalent à "Select * FROM serie ORDER BY name. Il n'y a que le query builder qui fonctionne ici.

                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
