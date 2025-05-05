<?php

// src/Controller/ProductController.php
namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/boutique', name: 'boutique')]
    public function index(ProductRepository $repo): Response
    {
        return $this->render('boutique/index.html.twig', [
            'products' => $repo->findAll(),
        ]);
    }
}
