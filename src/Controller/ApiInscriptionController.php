<?php

// src/Controller/ApiInscriptionController.php

namespace App\Controller;

use App\Entity\Inscription;
use App\Repository\InscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ApiInscriptionController extends AbstractController
{
    #[Route('/api/inscriptions', name: 'api_inscriptions', methods: ['GET'])]
    public function getInscriptions(InscriptionRepository $repo): JsonResponse
    {
        $inscriptions = $repo->findAll();
        
        // Vérifiez si des inscriptions sont récupérées
        dump($inscriptions); // ou dd($inscriptions) pour arrêter l'exécution ici
    
        $data = [];
        foreach ($inscriptions as $inscription) {
            $data[] = [
                'id' => $inscription->getId(),
                'nom' => $inscription->getNom(),
                'prenom' => $inscription->getPrenom(),
                'email' => $inscription->getEmail(),
                'tel' => $inscription->getTel(),
                'numLicence' => $inscription->getNumLicence(),
                'grade' => $inscription->getGrade(),
                'adresse' => $inscription->getAdresse(),
                'dateNaissance' => $inscription->getDateNaissance()->format('Y-m-d'),
            ];
        }
    
        return $this->json($data);
    }
    

    #[Route('/api/inscriptions', name: 'api_create_inscription', methods: ['POST'])]
    public function createInscription(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Récupérer les données envoyées
        $data = json_decode($request->getContent(), true);

        // Créer une nouvelle inscription
        $inscription = new Inscription();
        $inscription->setNom($data['nom']);
        $inscription->setPrenom($data['prenom']);
        $inscription->setEmail($data['email']);
        $inscription->setTel($data['tel']);
        $inscription->setNumLicence($data['numLicence']);
        $inscription->setGrade($data['grade']);
        $inscription->setAdresse($data['adresse']);
        $inscription->setDateNaissance(new \DateTime($data['dateNaissance']));

        // Enregistrer l'inscription dans la base de données
        $em->persist($inscription);
        $em->flush();

        // Répondre avec l'inscription créée
        return $this->json($inscription, 201);
    }
}

