<?php

namespace App\Controller;

use App\Entity\Creations;
use App\Form\CreationsType;
use App\Repository\CreationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/creations')]
class CreationsController extends AbstractController
{
    #[Route('/', name: 'app_creations_index', methods: ['GET'])]
    public function index(CreationsRepository $creationsRepository): Response
    {
        $creations = $creationsRepository->findAll();

        return $this->render('creations/index.html.twig', [
            'creations' => $creations,
        ]);
    }

    #[Route('/new', name: 'app_creations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $creation = new Creations();
        $form = $this->createForm(CreationsType::class, $creation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($creation);
            $entityManager->flush();

            return $this->redirectToRoute('app_creations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('creations/new.html.twig', [
            'creation' => $creation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_creations_show', methods: ['GET'])]
    public function show(Creations $creation): Response
    {
        return $this->render('creations/show.html.twig', [
            'creation' => $creation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_creations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Creations $creation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CreationsType::class, $creation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_creations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('creations/edit.html.twig', [
            'creation' => $creation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_creations_delete', methods: ['POST'])]
    public function delete(Request $request, Creations $creation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$creation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($creation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_creations_index', [], Response::HTTP_SEE_OTHER);
    }
}
