<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\InscriptionType;
use App\Entity\Inscription;
use Dompdf\Options;
use Dompdf\Dompdf;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\InscriptionFilterType;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function register(Request $request, MessageBusInterface $bus, EntityManagerInterface $em): Response
    {
        // Création d'un nouvel objet Inscription
        $inscription = new Inscription();
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        // Vérification si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données supplémentaires transmises via JavaScript
            $ageStatus = $request->request->get('popup_ageStatus');
            $ffjdaCom = $request->request->get('popup_ffjdaCom');
            $parentText = $request->request->get('popup_parentText');
            $imageRightText = $request->request->get('popup_imageRightText');
            $certifMed = $request->request->get('popup_certifMed');
            $paymentMethod = $request->request->get('popup_paymentMethod');
$signed = $request->request->get('popup_signed');

            // Persistance des données dans la base de données
            $em->persist($inscription);
            $em->flush();

            // Génération du contenu PDF
            $pdfContent = $this->renderView('inscription/pdf.html.twig', [
    'inscription' => $inscription,
    'ageStatus' => $ageStatus,
    'ffjdaCom' => $ffjdaCom,
    'parentText' => $parentText,
    'imageRightText' => $imageRightText,
    'certifMed' => $certifMed,
    'paymentMethod' => $paymentMethod,
    'signed' => $signed,
]);

            // Configuration de Dompdf pour générer le PDF
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($pdfContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfOutput = $dompdf->output();

            // Création de l'email avec le PDF en pièce jointe
            $email = (new \Symfony\Component\Mime\Email())
                ->from('valentinblin254@gmail.com')
                ->to('valentinblin254@gmail.com')
                ->subject('Inscription Judo Club')
                ->text('Veuillez trouver en pièce jointe le document d’inscription.')
                ->attach($pdfOutput, 'inscription.pdf', 'application/pdf');

            // Envoi de l'email via Messenger
            $sendEmailMessage = new SendEmailMessage($email);
            $bus->dispatch($sendEmailMessage);

            // Message flash de confirmation
            $this->addFlash('success', 'Votre inscription a bien été prise en compte et transmise au club.');


            // Redirection vers la page d'inscription
            return $this->redirectToRoute('inscription');
        }

        // Affichage du formulaire d'inscription
        return $this->render('inscription/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/test-mail', name: 'test_mail')]
    public function testMail(MessageBusInterface $bus): Response
    {
        // Création d'un email de test
        $email = (new \Symfony\Component\Mime\Email())
            ->from('test@example.com')
            ->to('test@example.com')
            ->subject('Test Mailtrap')
            ->text('Ceci est un test Mailtrap.');

        try {
            // Envoi de l'email via Messenger
            $sendEmailMessage = new SendEmailMessage($email);
            $bus->dispatch($sendEmailMessage);
            return new Response('Email envoyé avec succès via Messenger.');
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            return new Response('Erreur d’envoi : ' . $e->getMessage());
        }
    }
    
}


