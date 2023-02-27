<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();
        return $this->render('serie/list.html.twig', ["series"=>$series]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        if (!$serie) {
            throw  $this->createNotFoundException("Oops ! Serie not found !");
        }
        return $this->render('serie/show.html.twig', ["serie"=>$serie]);
    }

    #[Route('/add', name: 'add')]
    public function add(SerieRepository $serieRepository, Request $request): Response
    {

        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            $serieRepository->save($serie, true);

            $this->addFlash('success', "Serie added !");

            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        return $this->render('serie/add.html.twig', ['serieForm'=> $serieForm->createView()]);
    }
}
