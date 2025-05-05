<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JujitsuController extends AbstractController
{
    #[Route('/jujitsu', name: 'jujitsu')] // Ceci est la route pour "/judo"
    public function index(): Response
    {
        return $this->render('jujitsu/index.html.twig', [
            'controller_name' => 'JujitsuController',
        ]);
    }
}