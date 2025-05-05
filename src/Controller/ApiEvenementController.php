<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiEvenementController extends AbstractController
{
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
                'discipline' => $event->getDiscipline(),
                'color' => $event->getColor() ?? '#999999' ,
                'pdfUrl' => $event->getProspectus() 
                    ? '/uploads/prospectus/' . $event->getProspectus() 
                    : null,
            ];
        }

        return $this->json($data);
    }
}