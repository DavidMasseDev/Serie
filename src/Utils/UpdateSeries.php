<?php

namespace App\Utils;

use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;

class UpdateSeries
{
    // Passer par le constructeur est le seul moyen de pouvoir utiliser les repository et entity
    public function __construct(private EntityManagerInterface $entityManager, private SerieRepository $serieRepository){

    }
    public function removeOldSerie(): int
    {
        $cpt = 0;
        $series = $this->serieRepository->findAll();
        $date = new \DateTime("-20 years");
        foreach ($series as $serie){
            if($serie->getLastAirDate() < $date){
                $this->entityManager->remove($serie);
                $cpt++;
            }
        }
        $this->entityManager->flush();
        return $cpt;
    }
}