<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class PhotoController extends AbstractController
{
    #[Route('/admin/photo/{id}/delete', name: 'admin_photo_delete')]
    public function delete(Photo $photo, EntityManagerInterface $entityManager): RedirectResponse
{
    // Vérifie si une photo est associée à un fichier
    if ($photo->getImageName()) {
        $imagePath = $this->getParameter('photos_directory') . '/' . $photo->getImageName();
        
        // Si le fichier existe, tente de le supprimer
        if (file_exists($imagePath)) {
            try {
                unlink($imagePath); // Supprime le fichier image
            } catch (\Exception $e) {
                // Ajoute un message d'erreur si la suppression échoue
                $this->addFlash('error', 'Erreur lors de la suppression du fichier image: ' . $e->getMessage());
            }
        } else {
            // Ajoute un message d'avertissement si le fichier n'est pas trouvé
            $this->addFlash('warning', 'Le fichier de l\'image n\'existe pas.');
        }
    }

    // Supprime la photo de la base de données
    $entityManager->remove($photo);
    $entityManager->flush();

    // Redirige vers la liste des photos (ou une autre page)
    $this->addFlash('success', 'Photo supprimée avec succès.');
    return $this->redirectToRoute('galerie');
}
}
