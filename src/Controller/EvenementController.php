<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class EvenementController extends AbstractController
{
    // === FRONT ===

    #[Route('/evenement', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    // === ADMIN ===

    #[Route('/admin/evenement', name: 'admin_evenement_index')]
    public function adminIndex(EvenementRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/evenement/index.html.twig', [
            'evenements' => $repo->findAll(),
        ]);
    }

    #[Route('/admin/evenement/new', name: 'admin_evenement_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du fichier prospectus
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
                    $evenement->setProspectus($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                }
            }

            // Gestion des disciplines (en texte libre séparé par virgules)
            $disciplines = $form->get('disciplines')->getData();
            $evenement->setDisciplines($disciplines ?? []);

            $em->persist($evenement);
            $em->flush();

            return $this->redirectToRoute('admin_evenement_index');
        }

        return $this->render('admin/evenement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/evenement/{id}/delete', name: 'admin_evenement_delete')]
    public function delete(Evenement $evenement, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($evenement);
        $em->flush();

        return $this->redirectToRoute('admin_evenement_index');
    }

    // === API ===

    #[Route('/api/evenements', name: 'api_evenements')]
    public function getEvents(EvenementRepository $repo): JsonResponse
    {
        $evenements = $repo->findAll();
        $data = [];

        foreach ($evenements as $event) {
            $data[] = [
                'title' => $event->getTitre(),
                'start' => $event->getStart()->format('Y-m-d\TH:i:s'),
                'end' => $event->getEnd()->format('Y-m-d\TH:i:s'),
                'description' => $event->getDescription(),
                'disciplines' => $event->getDisciplines(), // <-- ici le tableau
                'color' => $event->getColor() ?? '#999999',
                'pdfUrl' => $event->getProspectus()
                    ? '/uploads/prospectus/' . $event->getProspectus()
                    : null,
            ];
        }

        return $this->json($data);
    }
}
