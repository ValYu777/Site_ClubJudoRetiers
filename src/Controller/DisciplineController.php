<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DisciplineController extends AbstractController
{
    #[Route('/discipline', name: 'discipline')]
    public function index(): Response
    {
        return $this->render('discipline/index.html.twig', [
            'controller_name' => 'DisciplineController',
        ]);
    }
}
