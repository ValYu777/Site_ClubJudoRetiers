<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Form\CompetitionType;
use App\Repository\CompetitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/competition')]
class CompetitionController extends AbstractController
{
    #[Route('/', name: 'admin_competition_index')]
    public function index(CompetitionRepository $competitionRepository): Response
    {
        return $this->render('admin/competition/index.html.twig', [
            'competitions' => $competitionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_competition_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $competition = new Competition();
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($competition);
            $em->flush();

            return $this->redirectToRoute('resultat');
        }

        return $this->render('admin/competition/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_competition_delete', methods: ['POST'])]
public function delete(Request $request, Competition $competition, EntityManagerInterface $em): Response
{
    if ($this->isCsrfTokenValid('delete' . $competition->getId(), $request->request->get('_token'))) {
        $em->remove($competition);
        $em->flush();
    }

    return $this->redirectToRoute('resultat');
}
}
