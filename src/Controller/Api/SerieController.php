<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

#[Route('/api/serie', name: 'api_serie_')]

class SerieController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();
        return $this->json($series, 200, [], ['groups'=>'serie_api']);
    }

    #[Route('/{id}', name: 'detail', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function detail(int $id): Response
    {
        return $this->render('', [

        ]);
    }

    #[Route('/', name: 'add', requirements: ['id'=>'\d+'], methods: ['POST'])]
    public function add(Request $request, Serializer $serializer): Response
    {
        $data = $request->getContent();
        $serie = $serializer->deserialize($data, Serie::class, 'json');
        dd($data);
    }
    #[Route('/{id}', name: 'update', requirements: ['id'=>'\d+'], methods: ['PUT', 'PATCH'])]
    public function update(int $id): Response
    {
        return $this->render('', [

        ]);
    }

    #[Route('/{id}', name: 'delete', requirements: ['id'=>'\d+'], methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        return $this->render('', [

        ]);
    }
}
