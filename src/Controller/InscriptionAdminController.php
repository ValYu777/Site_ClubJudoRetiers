<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Form\InscriptionFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/inscription')]
class InscriptionAdminController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(InscriptionRepository $inscriptionRepository): Response
    {
        return $this->render('admin/inscription/index.html.twig', [
            'inscriptions' => $inscriptionRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'admin_inscription_index')]
    public function list(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Liste des grades dans l'ordre avec des valeurs numériques
        $grades = [
            'Blanc' => 1,
            'Jaune' => 2,
            'Orange' => 3,
            'Verte' => 4,
            'Bleue' => 5,
            'Marron' => 6,
            'Noire' => 7,
        ];

        // Création du formulaire de filtre
        $filterForm = $this->createForm(InscriptionFilterType::class);
        $filterForm->handleRequest($request);

        // Construction de la requête de base pour récupérer les inscriptions
        $queryBuilder = $entityManager->getRepository(Inscription::class)->createQueryBuilder('i');

        // Application des filtres
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();

           /*  // Filtrer par grade minimal (si sélectionné)
            if ($data['gradeMin']) {
                // Convertir le grade min en valeur numérique
                $minGradeValue = $grades[$data['gradeMin']];
                $queryBuilder->andWhere('i.grade >= :gradeMin')
                             ->setParameter('gradeMin', $minGradeValue);
            }

            // Filtrer par grade maximal (si sélectionné)
            if ($data['gradeMax']) {
                // Convertir le grade max en valeur numérique
                $maxGradeValue = $grades[$data['gradeMax']];
                $queryBuilder->andWhere('i.grade <= :gradeMax')
                             ->setParameter('gradeMax', $maxGradeValue);
            } */

            // Filtrer par date de naissance minimale
            if ($data['dateNaissanceMin']) {
                $queryBuilder->andWhere('i.dateNaissance >= :dateNaissanceMin')
                             ->setParameter('dateNaissanceMin', $data['dateNaissanceMin']);
            }

            // Filtrer par date de naissance maximale
            if ($data['dateNaissanceMax']) {
                $queryBuilder->andWhere('i.dateNaissance <= :dateNaissanceMax')
                             ->setParameter('dateNaissanceMax', $data['dateNaissanceMax']);
            }
        }

        // Exécution de la requête et récupération des résultats
        $inscriptions = $queryBuilder->getQuery()->getResult();

        // Récupérer les e-mails des inscriptions
    $emails = array_map(function ($inscription) {
        return $inscription->getEmail(); // Assurez-vous que getEmail() existe dans votre entité Inscription
    }, $inscriptions);

        // Rendu de la page avec le formulaire et les résultats filtrés
        return $this->render('admin/inscription/list.html.twig', [
            'filterForm' => $filterForm->createView(),
            'inscriptions' => $inscriptions,
            'emails' => $emails, // Passer les emails à la vue
        ]);
    }

    // Route pour supprimer une inscription
    #[Route('/inscription/{id}/delete', name: 'inscription_delete')]
    public function delete(Inscription $inscription, EntityManagerInterface $entityManager): Response
    {
        // Supprimer l'inscription
        $entityManager->remove($inscription);
        $entityManager->flush();

        // Ajouter un message flash pour informer de la suppression
        $this->addFlash('success', 'Inscription supprimée avec succès.');

        // Rediriger vers la liste des inscriptions
        return $this->redirectToRoute('admin_inscription_index');
    }
}
