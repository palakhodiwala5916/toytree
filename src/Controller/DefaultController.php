<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController  extends AbstractFOSRestController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return new Response('Toy Connected!');
    }

    #[Route("/api/doc", name: "api_doc")]
    public function apiDoc(): Response
    {
        return $this->render('doc/api-doc.html.twig');
    }

}