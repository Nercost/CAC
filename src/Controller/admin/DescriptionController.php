<?php

namespace App\Controller\admin;

use App\Entity\Description;
use App\Form\DescriptionType;
use App\Repository\DescriptionRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\ReseauxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/description')]
class DescriptionController extends AbstractController
{
    #[Route('/', name: 'description_index', methods: ['GET'])]
    public function index(DescriptionRepository $descriptionRepository, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        return $this->render('description/index.html.twig', [
            'descriptions' => $descriptionRepository->findAll(),
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'description_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        $description = new Description();
        $form = $this->createForm(DescriptionType::class, $description);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($description);
            $entityManager->flush();

            return $this->redirectToRoute('entreprise_edit', ['id' => 1], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('description/new.html.twig', [
            'description' => $description,
            'form' => $form,
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'description_show', methods: ['GET'])]
    public function show(Description $description, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        return $this->render('description/show.html.twig', [
            'description' => $description,
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'description_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Description $description, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        $form = $this->createForm(DescriptionType::class, $description);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('entreprise_edit', ['id' => 1], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('description/edit.html.twig', [
            'description' => $description,
            'form' => $form,
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'description_delete', methods: ['POST'])]
    public function delete(Request $request, Description $description, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $description->getId(), $request->request->get('_token'))) {
            $entityManager->remove($description);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entreprise_edit', ['id' => 1], Response::HTTP_SEE_OTHER);
    }
}
