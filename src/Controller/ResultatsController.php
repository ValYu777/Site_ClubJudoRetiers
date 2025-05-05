<?php

// src/Controller/ResultatsController.php

namespace App\Controller;

use App\Repository\ResultatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultatsController extends AbstractController
{
    #[Route('/resultats', name: 'resultats')]
    public function index(ResultatsRepository $resultatRepository): Response
    {
        $resultats = $resultatRepository->findAll();

        return $this->render('resultats/index.html.twig', [
            'resultats' => $resultats,
        ]);
    }
}
