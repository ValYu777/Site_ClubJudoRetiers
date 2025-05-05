<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;  // <-- C'est ici !
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class HorairesController extends AbstractController
{
    #[Route('/horaires', name: 'horaires')]
    public function index(Request $request, CoursRepository $coursRepository, EntityManagerInterface $entityManager): Response
    {
        $cours_list = $coursRepository->findAll();

        $cours = new Cours();
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cours);
            $entityManager->flush();

            $this->addFlash('success', 'Cours ajouté avec succès !');

            return $this->redirectToRoute('horaires'); // rafraîchir la liste
        }

        return $this->render('horaires/index.html.twig', [
            'cours_list' => $cours_list,
            'form' => $form->createView(), // Très important !
        ]);
    }
}
