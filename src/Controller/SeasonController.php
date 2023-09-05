<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/season', name: 'season_')]
class SeasonController extends AbstractController
{
    #[Route('/{serieId}/new', name: 'new', requirements: ["id"=>'\d+'])]
    public function new(EntityManagerInterface $em, Request $request,  SerieRepository $serieRepository, int $serieId = 0,): Response
    {
        $season = new Season();
        if($serieId > 0){
            $serie = $serieRepository ->find($serieId);
            $season
                -> setSerie($serie)
                -> setNumber(count($serie->getSeasons())+1);
        }
        $seasonForm = $this->createForm(SeasonType::class, $season);
        $seasonForm->handleRequest($request); // handleRequest --> Extrait les informations de la requete et permet de setter les éléments dans l'objet Season.

        if($seasonForm->isSubmitted() && $seasonForm->isValid()){
            $season->setDateCreated(new \DateTime()); // Il serait mieux de la faire avec ORM\PrePersist dans l'entity Season
            $em->persist($season);
            $em->flush();
            $this->addFlash("success","Season added !");
            return $this->redirectToRoute("serie_show",["id"=>$season->getSerie()->getId()]);
        }
        return $this->render('season/new.html.twig', [
            "seasonForm" => $seasonForm,
        ]);
    }

    #[Route('/{id}/delete', name:'delete', requirements:['id'=>'\d+'])]
    public function delete(
        int $id,
        EntityManagerInterface $em,
        SerieRepository $serieRepository
    ): RedirectResponse
    {
        $serie = $serieRepository->find($id);
        $em->remove($serie);
        $em->flush();
        $this->addFlash("success", 'Serie deleted !');
        return $this->redirectToRoute('serie_list');

    }
}
