<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addSeries($manager);
    }

    private function addSeries(ObjectManager $manager){

        $faker = Factory::create('fr_FR'); // ::create = méthode statique.  Dans la classe Factoy, il n'y a que la méthode 'create' qui est publique.


        for($i = 0; $i<100; $i++) {
            $serie = new Serie();
            $serie
                ->setName($faker->streetName)
                ->setBackdrop('default-backdrop.png')
                ->setDateCreated($faker->dateTimeBetween('-2 years', 'now'))
                ->setGenre($faker->randomElement(['SF', 'Fantastic', 'Horror', 'Comedy', 'Thriller', 'Drama', 'Action']))
                ->setFirstAirDate($faker->dateTimeBetween('-30 years', $serie->getDateCreated()))
                ->setOverview($faker->realTextBetween(80, 255))
                ->setPopularity($faker->numberBetween(0, 1000))
                ->setPoster("default-poster.png")
                ->setTmdbId($faker->randomDigitNotNull)
                ->setVote($faker->numberBetween(0, 10))
                ->setStatus($faker->randomElement(['Running', 'Ending', 'Returning', 'Cancelled']));
            $manager->persist($serie);
        }
        $manager->flush();
    }
}
