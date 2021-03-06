<?php

namespace App\Controller\admin;

use App\Entity\Partenaire;
use App\Form\Partenaire1Type;
use App\Repository\EntrepriseRepository;
use App\Repository\PartenaireRepository;
use App\Repository\ReseauxRepository;
use App\Repository\ServicePartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/partenaire')]
class PartenaireController extends AbstractController
{
    #[Route('/', name: 'partenaire_index', methods: ['GET'])]
    public function index(PartenaireRepository $partenaireRepository, ServicePartenaireRepository $servicePartenaireRepository, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        return $this->render('partenaire/index.html.twig', [
            'partenaires' => $partenaireRepository->findAll(),
            'service_partenaires' => $servicePartenaireRepository->findAll(),
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'partenaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        $partenaire = new Partenaire();
        $form = $this->createForm(Partenaire1Type::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($partenaire);
            $entityManager->flush();

            return $this->redirectToRoute('partenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partenaire/new.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form,
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'partenaire_show', methods: ['GET'])]
    public function show(Partenaire $partenaire, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        return $this->render('partenaire/show.html.twig', [
            'partenaire' => $partenaire,
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'partenaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Partenaire $partenaire, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        $form = $this->createForm(Partenaire1Type::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('partenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partenaire/edit.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form,
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'partenaire_delete', methods: ['POST'])]
    public function delete(Request $request, Partenaire $partenaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $partenaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($partenaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('partenaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
