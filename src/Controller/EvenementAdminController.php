<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/evenement')]
class EvenementAdminController extends AbstractController
{
    #[Route('/', name: 'admin_evenement_index')]
    public function index(EvenementRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/evenement/index.html.twig', [
            'evenements' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_evenement_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prospectusFile = $form->get('prospectus')->getData();

            if ($prospectusFile) {
                $originalFilename = pathinfo($prospectusFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $prospectusFile->guessExtension();

                try {
                    $prospectusFile->move(
                        $this->getParameter('prospectus_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                }

                $evenement->setProspectus($newFilename);
            }

            $em->persist($evenement);
            $em->flush();

            return $this->redirectToRoute('admin_evenement_index');
        }

        return $this->render('admin/evenement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_evenement_delete')]
    public function delete(Evenement $evenement, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($evenement);
        $em->flush();

        return $this->redirectToRoute('admin_evenement_index');
    }

}