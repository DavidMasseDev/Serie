<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(): Response
    {
        $username = "David";
        $serie = ["name" => "Good Omens", "genre"=>"fantasy"];

        return $this->render('home/home.html.twig', [
            'username' => $username,
            'serie' => $serie
        ]);
    }

    #[Route('/test', name: 'home_test')]
    public function test(): Response
    {
        return $this->render('test/index.html.twig', [
        ]);
    }
}
