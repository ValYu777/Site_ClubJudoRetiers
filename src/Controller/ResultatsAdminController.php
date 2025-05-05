<?php


namespace App\Controller;

use App\Entity\Resultats;
use App\Form\ResultatsType;
use App\Repository\ResultatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/resultat')]
class ResultatsAdminController extends AbstractController
{
    #[Route('/', name: 'admin_resultat_index')]
    public function index(ResultatsRepository $resultatRepository): Response
    {
        return $this->render('admin/resultats/index.html.twig', [
            'resultats' => $resultatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_resultat_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $resultat = new Resultats();
        $form = $this->createForm(ResultatsType::class, $resultat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($resultat);
            $em->flush();

            return $this->redirectToRoute('admin_resultat_index');
        }

        return $this->render('admin/resultats/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_resultat_delete')]
    public function delete(Resultats $resultat, EntityManagerInterface $em): Response
    {
        $em->remove($resultat);
        $em->flush();

        return $this->redirectToRoute('admin_resultat_index');
    }
}
