<?php

namespace App\Controller;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Photo;


final class GalerieController extends AbstractController
{
    #[Route('/galerie', name: 'galerie')]
    public function index(AlbumRepository $albumRepository, PhotoRepository $photoRepository): Response
    {
        // On récupère tous les albums
        $albums = $albumRepository->findAll();

        // On récupère toutes les photos, triées par date de manière décroissante
        $photos = $photoRepository->findBy([], ['updatedAt' => 'DESC']);

        // On passe les albums et les photos à la vue
        return $this->render('galerie/index.html.twig', [
            'albums' => $albums,
            'photos' => $photos,
        ]);
    }

    #[Route('/galerie/{id}', name: 'galerie_show', requirements: ['id' => '\d+'])]
    public function show(
        Album $album,
        Request $request,
        EntityManagerInterface $entityManager,
        PhotoRepository $photoRepository
    ): Response {
        // Création de la nouvelle photo liée à l'album
        $photo = new Photo();
        $photo->setAlbum($album);
    
        // Création du formulaire
        $form = $this->createForm(\App\Form\PhotoType::class, $photo);
        $form->handleRequest($request);
    
        // Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($photo);
            $entityManager->flush();
    
            $this->addFlash('success', 'Photo ajoutée avec succès.');
    
            return $this->redirectToRoute('galerie_show', ['id' => $album->getId()]);
        }
    
        // Récupération des photos du bon album
        $photos = $photoRepository->findBy(['album' => $album], ['updatedAt' => 'DESC']);
    
        return $this->render('galerie/show.html.twig', [
            'album' => $album,
            'photos' => $photos,
            'form' => $form->createView(),
        ]);
    }
}
