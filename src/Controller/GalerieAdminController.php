<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Album;
use App\Form\PhotoType;
use App\Repository\AlbumRepository;
use App\Repository\PhotoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/galerie')]
class GalerieAdminController extends AbstractController
{
    #[Route('/', name: 'admin_galerie_index')]
    public function index(AlbumRepository $albumRepository, PhotoRepository $photoRepository): Response
    {
        // Récupérer tous les albums
        $albums = $albumRepository->findAll();
        
        // Récupérer les photos associées à chaque album
        $photos = $photoRepository->findAll();

        return $this->render('admin/galerie/index.html.twig', [
            'albums' => $albums, // Passer les albums
            'photos' => $photos, // Passer les photos
        ]);
    }

    #[Route('/photo/new', name: 'admin_galerie_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('fichier')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );

                $photo->setFichier($newFilename);
                $photo->setDate(new \DateTimeImmutable());

                // Récupérer l'album sélectionné
                $album = $form->get('album')->getData();
                $photo->setAlbum($album);

                $em->persist($photo);
                $em->flush();

                return $this->redirectToRoute('galerie');
            }
        }

        return $this->render('galerie/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/photo/{id}/delete', name: 'admin_galerie_delete')]
    public function delete(Photo $photo, EntityManagerInterface $em): Response
    {
        $filepath = $this->getParameter('photos_directory').'/'.$photo->getFichier();
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $em->remove($photo);
        $em->flush();

        return $this->redirectToRoute('galerie');
    }
}
