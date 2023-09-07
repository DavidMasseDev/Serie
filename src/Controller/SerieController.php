<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Utils\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{

    // Un Repository sert a RECUPERER des données. Il n'est pas utile pour le C-U-D du CRUD
    #[Route('/list/{page}', name: 'list')]
    public function list(SerieRepository $serieRepository, int $page = 1): Response // Par défaut, la page est la première
    {
        //$series = $serieRepository->findAll();
        //$series = $serieRepository->findBy([], ["popularity" => "DESC"], 50);
        //$series = $serieRepository->findBestSeriesDQL();
        //$series = $serieRepository->findBestSeries(200);
        $countSeries = $serieRepository->count([]);
        $maxPage = ceil($countSeries / 50);
        if ($page < 1) {
            $page = 1;
        }
        if ($page <= $maxPage) {
            $series = $serieRepository->findSeriesWithPaginator($page);

        } else {
            throw $this->createNotFoundException("Page not found.");
        }

        dump($series);
        return $this->render('serie/list.html.twig',
            [
                "series" => $series,
                "currentPage" => $page,
                "maxPage" => $maxPage,
            ]);
    }


    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(SerieRepository $serieRepository, int $id): Response
    {
        $serie = $serieRepository->find($id);
        if (!$serie) {
            throw $this->createNotFoundException('Oooops ! Serie not found');
        }
        return $this->render('serie/show.html.twig',
            [
                // Pas besoin de passer une variable Season au twig, car on récupère la saison depuis l'entité Serie
                "serie" => $serie,
            ]);


    }


    #[Route('/new', name: 'new', requirements: ['id' => '\d+'])]
    //#[IsGranted('ROLE_ADMIN')] // Ici la route est accessible uniquement si l'utilisateur a un role_user (donc connecté)
    public function new(EntityManagerInterface $em, Request $request, Uploader $uploader): Response
    {
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        //Extrait les données de la requête et "set" ces données dans l'instance "Série" de createForm(SerieType::class, $serie)
        $serieForm->handleRequest($request);
        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            // La date est maintenant mise automatiquement (voir SerieEntity->setDateAtValue() = Orm\PrePersist )
            //$serie->setDateCreated(new \DateTime());
            // @ var UploadedFile Permet de dire a PhpStorm que l'image est de type 'UploadedFile' --> utile uniquement pour l'autocomplétion
            /**
             * @var UploadedFile $image
             */
            $image = $serieForm->get('poster')->getData();
            // Il vaut mieux faire l'upload d'image grâce à un service
//            if ($image) {
//                $newFileName = $serie->getName() . '-' . uniqid() . '.' . $image->guessExtension();
//                $image->move($this->getParameter('upload_serie_dir'), $newFileName); // upload_serie_dir vient se services.yaml. getParameter ne peut etre utilisé que dans le controller
//                $serie->setPoster($newFileName);
//            }
            //Utilisation du service Utils/Uploader
            $serie->setPoster(
                $uploader->uploadImage(
                    $serieForm->get('poster')->getData(),
                    $this->getParameter('upload_poster_serie_dir'),
                    $serie->getName()
                ));
            $serie->setPoster(
                $uploader->uploadImage(
                    $serieForm->get('backdrop')->getData(),
                    $this->getParameter('upload_backdrop_serie_dir'),
                    $serie->getName()
                ));
            $em->persist($serie);
            $em->flush();
            $this->addFlash("success", "La série " . $serie->getName() . " a bien été crée.");
            return $this->redirectToRoute('serie_show', ["id" => $serie->getId()]);
        }

        return $this->render('serie/new.html.twig', [
            "serieForm" => $serieForm,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(
        EntityManagerInterface $em,
        Request                $request,
        SerieRepository        $serieRepository,
        int                    $id): Response
    {

        $serie = $serieRepository->find($id);
        $serieForm = $this->createForm(SerieType::class, $serie);

        //Extrait les données de la requête et "set" ces données dans l'instance "Série" de createForm(SerieType::class, $serie)
        $serieForm->handleRequest($request);
        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            // La date est maintenant mise automatiquement (voir SerieEntity->setDateAtValue() )
            //$serie->setDateCreated(new \DateTime());
            $em->persist($serie);
            $em->flush();
            $this->addFlash("success", "Update completed (" . $serie->getName() . ") !");
            return $this->redirectToRoute('serie_show', ["id" => $serie->getId()]);
        }

        return $this->render('serie/new.html.twig', [
            "serieForm" => $serieForm,
        ]);
    }

    #[Route('delete/{id}', name: 'delete', requirements: ["id" => '\d+'])]
//    #[ParamConverter("serie", class: Serie::class)]
//         ParamConverter n'est plus utile, il fait directement le lien entre le parametre de la route (id) et l'attribut dans la fonction (Serie $serie).
//         Il est possible d'avoir plusieurs parametres et attribut. L'ordre est donc important
//         https://symfony.com/blog/new-in-symfony-6-2-built-in-cache-security-template-and-doctrine-attributes
    #[IsGranted("SERIE_DELETE", "serie", "You can't delete this serie", '404')] //Utilisation du Voter de SerieVoter -- "You can't... = Message d'erreur qui s'affiche si on a pas le droit // 404 = code erreur que l'on souhaite renvoyer
    public function delete(Serie $serie, EntityManagerInterface $em, SerieRepository $serieRepository): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        $em->remove($serie);
        $em->flush();

        $this->addFlash('success', 'Serie ' . $serie->getName() . ' deleted !');
        return $this->redirectToRoute('serie_list');
    }


}
