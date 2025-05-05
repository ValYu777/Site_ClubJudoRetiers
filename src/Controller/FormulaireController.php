<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FormulaireController extends AbstractController
{
    #[Route('/formulaire', name: 'formulaire')]
    public function index(): Response
    {
        return $this->render('formulaire/index.html.twig', [
            'controller_name' => 'FormulaireController',
        ]);
    }

    public function generatePdf(Inscription $data): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('formulaire/pdf.html.twig', ['data' => $data]);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("inscription.pdf", ["Attachment" => true]);
        return new Response();
    }
}
