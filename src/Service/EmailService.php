<?php

// src/Service/EmailService.php
namespace App\Service;

use App\Entity\User;
use App\Entity\Evenement;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class EmailService
{
    private $bus;
    private $mailer;

    public function __construct(MessageBusInterface $bus, MailerInterface $mailer)
    {
        $this->bus = $bus;
        $this->mailer = $mailer;
    }

    // Méthode générique pour envoyer un email
    public function sendEmail($to, $subject, $body)
    {
        // Créer l'email à envoyer
        $email = (new Email())
            ->from('example@domain.com')
            ->to($to)
            ->subject($subject)
            ->text($body);

        // Envoi de l'email via le service MailerInterface
        $this->mailer->send($email);
    }

    public function sendReminder(string $email, Evenement $event): void
    {
        $emailMessage = (new Email())
            ->from('club@example.com')  // L'email de l'expéditeur
            ->to($email)                // L'email du destinataire
            ->subject("Rappel : Événement « {$event->getTitre()} » bientôt !")
            ->text("Bonjour,\n\nL'événement « {$event->getTitre()} » aura lieu le {$event->getStart()->format('d/m/Y')}.\n\nÀ bientôt !");

        $this->mailer->send($emailMessage);
    }
}
