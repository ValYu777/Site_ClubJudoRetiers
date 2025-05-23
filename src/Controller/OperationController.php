<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\OperationType;
use App\Repository\OperationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class OperationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // === Front Office ===

    #[Route('/operations', name: 'operation_index')]
    public function index(OperationRepository $operationRepository): Response
    {
        $operations = $operationRepository->findBy([], ['id' => 'DESC']);

        return $this->render('operation/index.html.twig', [
            'operations' => $operations,
        ]);
    }

    #[Route('/operations/{id}', name: 'operation_show')]
    public function show(Operation $operation): Response
    {
        return $this->render('operation/show.html.twig', [
            'operation' => $operation,
        ]);
    }

    #[Route('/api/operations', name: 'api_operations')]
    public function api(OperationRepository $repo): JsonResponse
    {
        $operations = $repo->findAll();

        $data = array_map(function ($operation) {
            return [
                'title' => $operation->getNom(),
                'start' => $operation->getDateDebut()->format('Y-m-d'),
                'end' => (clone $operation->getDateFin())->modify('+1 day')->format('Y-m-d'),
                'display' => 'background',
                'backgroundColor' => '#524f4f'
            ];
        }, $operations);

        return $this->json($data);
    }

    // === Admin ===

    #[Route('/admin/operation', name: 'admin_operation_index')]
    public function adminIndex(OperationRepository $operationRepository): Response
    {
        $operations = $operationRepository->findAll();

        return $this->render('admin/operation/index.html.twig', [
            'operations' => $operations,
        ]);
    }

    #[Route('/admin/operation/new', name: 'admin_operation_new')]
    public function new(Request $request): Response
    {
        $operation = new Operation();
        $form = $this->createForm(OperationType::class, $operation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('operations_images_directory'),
                        $newFilename
                    );
                    $operation->setImagePath($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                    return $this->redirectToRoute('admin_operation_new');
                }
            }

            $this->entityManager->persist($operation);
            $this->entityManager->flush();

            $this->addFlash('success', 'Opération créée avec succès.');
            return $this->redirectToRoute('operation_index');
        }

        return $this->render('admin/operation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/operation/delete/{id}', name: 'admin_operation_delete')]
    public function delete(Operation $operation): Response
    {
        $this->entityManager->remove($operation);
        $this->entityManager->flush();

        $this->addFlash('success', 'Opération supprimée avec succès.');
        return $this->redirectToRoute('operation_index');
    }
}
