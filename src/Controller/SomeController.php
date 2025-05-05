<?php

// src/Controller/SomeController.php
namespace App\Controller;

use App\Service\EmailService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SomeController
{
    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(): Response
    {
        // Envoi d'un email automatiquement
        $this->emailService->sendEmail('user@example.com', 'Welcome', 'Hello, welcome to our platform!');

        return new Response('Email sent!');
    }
}
