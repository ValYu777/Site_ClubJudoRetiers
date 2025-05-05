<?php

// src/Controller/AdminController.php
namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cours;
use App\Repository\CoursRepository;

class AdminController extends AbstractController
{
  
    #[Route('/admin/ajouter-evenement', name: 'ajouter_evenement')]
    public function ajouterEvenement(Request $request): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('admin/ajouter_evenement.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        // Code pour afficher le dashboard de l'admin
        //return $this->render('admin/dashboard.html.twig');
        return $this->redirectToRoute('home');

    }

    #[Route('/admin/cours/{id}/delete', name: 'admin_cours_delete', methods: ['POST'])]
public function delete(Cours $cours, CoursRepository $coursRepository): Response
{
    // Supprimer le cours
    $coursRepository->remove($cours, true);

    $this->addFlash('success', 'Le cours a été supprimé avec succès.');
    return $this->redirectToRoute('admin_cours_index');
}
}
