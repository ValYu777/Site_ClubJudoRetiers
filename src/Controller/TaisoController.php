<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaisoController extends AbstractController
{
    #[Route('/taiso', name: 'taiso')] // Ceci est la route pour "/judo"
    public function index(): Response
    {
        return $this->render('taiso/index.html.twig', [
            'controller_name' => 'TaisoController',
        ]);
    }
}