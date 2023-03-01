<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Utils\Uploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list/{page}', name: 'list', requirements: ["page"=>"\d+"], methods: "GET")]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {
        $nbSerieMax = $serieRepository->count([]);
        $maxPage = ceil($nbSerieMax/SerieRepository::SERIE_LIMIT);

        if ($page >= 1 && $page <= $maxPage) {
            $series = $serieRepository->findBestSeries($page);
        }else {
            throw $this->createNotFoundException("Oops ! Page Not found !");
        }
        return $this->render('serie/list.html.twig', ["series"=>$series, "currentPage"=>$page, "maxPage"=>$maxPage]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(Serie $id): Response
    {
        if (!$id) {
            throw  $this->createNotFoundException("Oops ! Serie not found !");
        }
        return $this->render('serie/show.html.twig', ["serie"=>$id]);
    }

    #[Route('/add', name: 'add')]
    public function add(SerieRepository $serieRepository, Request $request, Uploader $uploader): Response
    {

        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            $poster = $serieForm->get("poster")->getData();
            $backdrop = $serieForm->get("backdrop")->getData();

            $serie
                ->setPoster($uploader->upload(
                    $poster,
                    $this->getParameter('upload_serie_poster'),
                    $serie->getName()))
                ->setBackdrop($uploader->upload(
                    $backdrop,
                    $this->getParameter('upload_serie_backdrop'),
                    $serie->getName()));

            $serieRepository->save($serie, true);

            $this->addFlash('success', "Serie added !");

            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        return $this->render('serie/add.html.twig', ['serieForm'=> $serieForm->createView()]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(SerieRepository $serieRepository, Serie $id): Response
    {

        if ($id) {
            $serieRepository->remove($id, true);
            $this-> addFlash("warning", "Serie deleted !");
        } else {
            throw  $this->createNotFoundException("Oops ! Delete not found !");
        }

        return $this->redirectToRoute('serie_list');
    }
}
