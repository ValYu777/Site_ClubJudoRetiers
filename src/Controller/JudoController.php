<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JudoController extends AbstractController
{
    #[Route('/judo', name: 'judo')] // Ceci est la route pour "/judo"
    public function index(): Response
    {
        return $this->render('judo/index.html.twig', [
            'controller_name' => 'JudoController',
        ]);
    }
}