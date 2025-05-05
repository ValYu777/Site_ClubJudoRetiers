<?php
// src/Controller/HomeController.php

namespace App\Controller;

use App\Entity\Evenement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'home')] 
    public function index(): Response
    {
        // Récupérer tous les événements depuis la base de données
        $evenements = $this->entityManager->getRepository(Evenement::class)->findAll();

        // Passer les événements à la vue
        return $this->render('home/index.html.twig', [
            'evenements' => $evenements
        ]);
    }
}
