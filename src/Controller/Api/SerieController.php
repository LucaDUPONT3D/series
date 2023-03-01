<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/serie', name: 'api_serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'retrieve_all', methods: 'GET')]
    public function retrieveALL(): Response
    {
        //TODO return all
    }

    #[Route('/{id}', name: 'retrieve_one', methods: 'GET')]
    public function retrieveOne(): Response
    {
        //TODO return one
    }

    #[Route('', name: 'add', methods: 'POST')]
    public function add(): Response
    {
        //TODO add one
    }

    #[Route('/{id}', name: 'remove', methods: 'DELETE')]
    public function remove(): Response
    {
        //TODO remove one
    }

    #[Route('/{id}', name: 'update', methods: 'PUT')]
    public function update(): Response
    {
        //TODO update one
    }
}
