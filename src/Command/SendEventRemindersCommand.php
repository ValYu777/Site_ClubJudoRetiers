<?php

// src/Command/SendEventRemindersCommand.php
namespace App\Command;

use App\Service\EmailService;
use App\Entity\Evenement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Inscription;

class SendEventRemindersCommand extends Command
{
    private $entityManager;
    private $emailService;

    public function __construct(EntityManagerInterface $entityManager, EmailService $emailService)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->emailService = $emailService;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:send-event-reminders')
            ->setDescription('Envoie des rappels par email pour les événements à venir.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupérer les événements à venir (par exemple dans une semaine)
        $now = new \DateTime();
        $oneWeekLater = (new \DateTime())->modify('+1 week');
        $events = $this->entityManager->getRepository(Evenement::class)->findUpcomingEvents($now, $oneWeekLater);

         // Récupération des inscriptions pour collecter les emails
    $inscriptions = $this->entityManager->getRepository(Inscription::class)->findAll();

    // Extraire les adresses email uniques
    $emails = [];
    foreach ($inscriptions as $inscription) {
        $email = $inscription->getEmail(); // Assure-toi que ce getter existe
        if ($email && !in_array($email, $emails)) {
            $emails[] = $email;
        }
    }

        foreach ($events as $event) {
            // Pour chaque événement, envoyer un email aux utilisateurs
            foreach ($emails as $email) {
                // Envoi du rappel via le service EmailService
                $this->emailService->sendReminder($email, $event);
                $output->writeln("Rappel envoyé à : $email pour l'événement : " . $event->getTitre());
            }
        }

        return Command::SUCCESS;
    }
}
