<?php

namespace App\Controller;

use App\Entity\Resultat;
use App\Form\ResultatType;
use App\Repository\CompetitionRepository;
use App\Repository\ResultatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultatController extends AbstractController
{
    // === FRONT ===

    #[Route('/resultat', name: 'resultat')]
    public function index(CompetitionRepository $competitionRepository): Response
    {
        $competitions = $competitionRepository->findBy([], ['id' => 'DESC']);

        return $this->render('resultat/index.html.twig', [
            'competitions' => $competitions,
        ]);
    }

    // === ADMIN ===

    #[Route('/admin/resultat', name: 'admin_resultat_index')]
    public function adminIndex(ResultatRepository $resultatRepository): Response
    {
        return $this->render('admin/resultat/index.html.twig', [
            'resultats' => $resultatRepository->findAll(),
        ]);
    }

    #[Route('/admin/resultat/new/{competition_id}', name: 'admin_resultat_new')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        int $competition_id,
        CompetitionRepository $competitionRepository
    ): Response {
        $competition = $competitionRepository->find($competition_id);

        if (!$competition) {
            throw $this->createNotFoundException('Compétition introuvable');
        }

        $resultat = new Resultat();
        $resultat->setCompetition($competition);

        $form = $this->createForm(ResultatType::class, $resultat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($resultat);
            $em->flush();

            return $this->redirectToRoute('resultat');
        }

        return $this->render('admin/resultat/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/resultat/delete/{id}', name: 'admin_resultat_delete', methods: ['POST'])]
    public function delete(Request $request, Resultat $resultat, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $resultat->getId(), $request->request->get('_token'))) {
            $em->remove($resultat);
            $em->flush();
        }

        return $this->redirectToRoute('resultat');
    }
}
