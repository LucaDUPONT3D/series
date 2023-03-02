<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/serie', name: 'api_serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'retrieve_all', methods: 'GET')]
    public function retrieveALL(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();
        return $this->json($series, 200, [], ['groups'=>'serie_api']);
    }

    #[Route('/{id}', name: 'retrieve_one', methods: 'GET')]
    public function retrieveOne(Serie $id): Response
    {
        return $this->json($id, 200, [], ['groups'=>'serie_api']);
    }

    #[Route('', name: 'add', methods: 'POST')]
    public function add(Request $request, SerializerInterface $serializer): Response
    {
        $serie = $serializer->deserialize($request->getContent(), Serie::class, 'json');

        //TODO sauvegarder en BDD

        return $this->json("OK");

    }

    #[Route('/{id}', name: 'remove', methods: 'DELETE')]
    public function remove(): Response
    {
        //TODO remove one
    }

    #[Route('/{id}', name: 'update', methods: 'PUT')]
    public function update(Serie $id, Request $request, SerieRepository $serieRepository): Response
    {
        $data = json_decode($request->getContent());

        if ($data->like) {
            $id->setNbLike($id->getNbLike()+1);
        }else {
            $id->setNbLike($id->getNbLike()-1);
        }

        $serieRepository->save($id, true);

        return $this->json(['nbLike'=> $id->getNbLike()]);

    }
}
