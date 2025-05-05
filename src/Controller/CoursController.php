<?php

// src/Controller/CoursController.php

namespace App\Controller;

use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    private $coursRepository;

    public function __construct(CoursRepository $coursRepository)
    {
        $this->coursRepository = $coursRepository;
    }

    #[Route('/api/cours', name: 'api_cours', methods: ['GET'])]
    public function getCours(): JsonResponse
    {
        $cours = $this->coursRepository->findAll();  // Récupère tous les cours

        // Date du lundi de la semaine en cours
        $startOfWeek = new \DateTime();
        $startOfWeek->setISODate($startOfWeek->format('Y'), $startOfWeek->format('W')); // Fixe au lundi de la semaine actuelle
        $startOfWeek->setTime(0, 0, 0);  // Définir l'heure à minuit pour le lundi

        // Formatage des cours pour FullCalendar
        $formattedCours = [];
        foreach ($cours as $coursItem) {
            // Pour chaque cours, on fixe l'heure et on ajoute à la date du lundi
            $courseDay = $coursItem->getJour(); // Supposons que tu as un champ jour (Lundi, Mardi, etc.)

            // Calculer la date exacte pour chaque jour
            $courseDate = clone $startOfWeek;
            switch ($courseDay) {
                case 'Lundi':
                    $courseDate->modify('monday');
                    break;
                case 'Mardi':
                    $courseDate->modify('tuesday');
                    break;
                case 'Mercredi':
                    $courseDate->modify('wednesday');
                    break;
                case 'Jeudi':
                    $courseDate->modify('thursday');
                    break;
                case 'Vendredi':
                    $courseDate->modify('friday');
                    break;
                case 'Samedi':
                    $courseDate->modify('saturday');
                    break;
                case 'Dimanche':
                    $courseDate->modify('sunday');
                    break;
                default:
                    // Gérer les jours invalides si nécessaire
                    break;
            }

            // Ajuste l'heure de début et de fin
            $courseDate->setTime((int)$coursItem->getHeureDebut()->format('H'), (int)$coursItem->getHeureDebut()->format('i'));
            $endTime = clone $courseDate;
            $endTime->setTime((int)$coursItem->getHeureFin()->format('H'), (int)$coursItem->getHeureFin()->format('i'));

            // Formater pour FullCalendar
            $formattedCours[] = [
                'title' => $coursItem->getDiscipline(),  // Nom de la discipline
                'start' => $courseDate->format('Y-m-d\TH:i:s'),
                'end' => $endTime->format('Y-m-d\TH:i:s'),
                'color' => $coursItem->getColor() ?? '#999999' ,
                'discipline' => $coursItem->getDiscipline(),
            ];
        }

        return new JsonResponse($formattedCours);
    }
}
