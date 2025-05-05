<?php

// src/Controller/AdminCoursController.php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/cours')]
class AdminCoursController extends AbstractController
{
    #[Route('/', name: 'admin_cours_index')]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('admin/cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_cours_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $cours = new Cours();
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($cours);
            $em->flush();

            return $this->redirectToRoute('admin_cours_index');
        }

        return $this->render('admin/cours/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_cours_delete')]
    public function delete(Cours $cours, EntityManagerInterface $em): Response
    {
        $em->remove($cours);
        $em->flush();

        return $this->redirectToRoute('admin_cours_index');
    }
}
