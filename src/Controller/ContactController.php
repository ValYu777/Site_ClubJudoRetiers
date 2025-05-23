<?php

// src/Controller/ContactController.php
namespace App\Controller;

use App\Entity\ContactMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;



class ContactController extends AbstractController
{
   
    #[Route('/contact', name: 'contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig');
    }


    #[Route('/contact/submit', name: 'contact_submit')]
    public function submit(Request $request, EntityManagerInterface $em)
    {
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $message = $request->request->get('message');

        $contactMessage = new ContactMessage();
        $contactMessage->setName($name);
        $contactMessage->setEmail($email);
        $contactMessage->setMessage($message);
        $contactMessage->setCreatedAt(new \DateTime());

        $em->persist($contactMessage);
        $em->flush();

        $this->addFlash('success', 'Votre message a bien été envoyé.');

        return $this->redirectToRoute('contact');
    }

       #[Route('/admin/messages', name: 'admin_messages')] 
public function listMessages(EntityManagerInterface $em)
{
    $messages = $em->getRepository(ContactMessage::class)->findBy([], ['createdAt' => 'DESC']);

    return $this->render('admin/messages/index.html.twig', [
        'messages' => $messages,
    ]);
}
}
