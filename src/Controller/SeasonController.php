<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use App\Utils\Uploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/season', name: 'season_')]
class SeasonController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(Request $request, SeasonRepository $seasonRepository, Uploader $uploader): Response
    {
        $season = new Season();
        $seasonForm = $this->createForm(SeasonType::class, $season);
        $seasonForm->handleRequest($request);
        if ($seasonForm->isSubmitted() && $seasonForm->isValid()) {

            $poster = $seasonForm->get("poster")->getData();
            $season
                ->setPoster($uploader->upload(
                    $poster,
                    $this->getParameter('upload_serie_poster'),
                    $season->getSerie()->getName() . $season->getNumber()));

            $seasonRepository->save($season, true);

            $this->addFlash('success', "Serie added !");

            return $this->redirectToRoute('serie_show', ['id' => $season->getSerie()->getId()]);
        }

        return $this->render('season/add.html.twig', ['seasonForm' => $seasonForm->createView()]);
    }
}
