<?php
// src/Controller/HomeController.php

namespace App\Controller;

use App\Entity\Evenement;
use App\Repository\AlbumRepository;
use App\Repository\OperationRepository;
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
public function index(
    AlbumRepository $albumRepository,
    OperationRepository $operationRepository
): Response {
    // Récupérer tous les événements
    $evenements = $this->entityManager->getRepository(Evenement::class)->findAll();

    // Récupérer le dernier album par ID décroissant
    $lastAlbum = $albumRepository->findOneBy([], ['id' => 'DESC']);

    // Récupérer la dernière opération par ID décroissant
    $lastOperation = $operationRepository->findOneBy([], ['id' => 'DESC']);

    return $this->render('home/index.html.twig', [
        'evenements' => $evenements,
        'lastAlbum' => $lastAlbum,
        'lastOperation' => $lastOperation,
    ]);
}
}
