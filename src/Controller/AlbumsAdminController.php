<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Photo;
use App\Form\PhotoType;


#[Route('/admin/album')]
class AlbumAdminController extends AbstractController
{
    #[Route('/new', name: 'admin_album_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($album);
            $em->flush();

            return $this->redirectToRoute('galerie');
        }

        return $this->render('admin/album/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_album_show', methods: ['GET', 'POST'])]
public function show(Album $album, Request $request, EntityManagerInterface $em): Response
{
    $photo = new Photo();
    $form = $this->createForm(PhotoType::class, $photo);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photo->setAlbum($album);
        $em->persist($photo);
        $em->flush();

        return $this->redirectToRoute('admin_album_show', ['id' => $album->getId()]);
    }

    return $this->render('admin/galerie/show.html.twig', [
        'album' => $album,
        'form' => $form->createView(),
    ]);
}

#[Route('/admin/album/{id}', name: 'admin_album_delete', methods: ['POST'])]
public function delete(Request $request, Album $album, EntityManagerInterface $em): Response
{
    if ($this->isCsrfTokenValid('delete' . $album->getId(), $request->request->get('_token'))) {
        $em->remove($album);
        $em->flush();
    }

    return $this->redirectToRoute('galerie');
}

}
