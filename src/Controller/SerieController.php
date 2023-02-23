<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        //$series = $serieRepository->findAll();
        //$series = $serieRepository->findBy(["status"=> "ended"], ["popularity" => "DESC"], 10 , 10);
        //$series = $serieRepository->findBy([], ["vote" => "DESC"], 50);
        //$series = $serieRepository->findByStatus("ended");
        $series = $serieRepository->findBestSeries();

        dump($series);

        return $this->render('serie/list.html.twig', ["series"=>$series]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        if (!$serie){
            throw  $this->createNotFoundException("Oops ! Serie not found !");
        }
        return $this->render('serie/show.html.twig', ["serie"=>$serie]);
    }

    #[Route('/add', name: 'add')]
    public function add(SerieRepository $serieRepository, EntityManagerInterface $entityManager): Response
    {

        $serie = new Serie();
        $serie
            ->setName("Le magicien")
            ->setBackdrop("backdrop.png")
            ->setDateCreated(new \DateTime())
            ->setGenres("Comedy")
            ->setFirstAirDate(new \DateTime('2005-02-02'))
            ->setLastAirDate(new \DateTime('-6 month'))
            ->setPopularity(850.52)
            ->setPoster("poster.png")
            ->setTmdbId(123456)
            ->setVote(8.5)
            ->setStatus("ended");

        $entityManager->persist($serie);
        $entityManager->flush();

        $serieRepository->remove($serie, true);

//        dump($serie);
//
//        $serieRepository->save($serie, true);
//
//        dump($serie);
//
//        $serie->setName("The last of us");
//        $serieRepository->save($serie, true);
//
//        dump($serie);


        //TODO Créer un formulaire d'ajout de série
        return $this->render('serie/add.html.twig');
    }
}
