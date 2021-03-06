<?php

namespace App\Controller\admin;

use App\Entity\Reseaux;
use App\Entity\Temoignage;
use App\Form\TemoignageType;
use App\Repository\EntrepriseRepository;
use App\Repository\ReseauxRepository;
use App\Repository\TemoignageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/temoignage')]
class TemoignageController extends AbstractController
{
    #[Route('/', name: 'temoignage_index', methods: ['GET'])]
    public function index(TemoignageRepository $temoignageRepository, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        return $this->render('temoignage/index.html.twig', [
            'temoignages' => $temoignageRepository->findAll(),
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'temoignage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        $temoignage = new Temoignage();
        $form = $this->createForm(TemoignageType::class, $temoignage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($temoignage);
            $entityManager->flush();

            return $this->redirectToRoute('temoignage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('temoignage/new.html.twig', [
            'temoignage' => $temoignage,
            'form' => $form,
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'temoignage_show', methods: ['GET'])]
    public function show(Temoignage $temoignage, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        return $this->render('temoignage/show.html.twig', [
            'temoignage' => $temoignage,
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'temoignage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Temoignage $temoignage, EntityManagerInterface $entityManager, EntrepriseRepository $entrepriseRepository, ReseauxRepository $reseauxRepository): Response
    {
        $form = $this->createForm(TemoignageType::class, $temoignage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('temoignage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('temoignage/edit.html.twig', [
            'temoignage' => $temoignage,
            'form' => $form,
            'entreprise' => $entrepriseRepository->find(1),
            'reseaux' => $reseauxRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'temoignage_delete', methods: ['POST'])]
    public function delete(Request $request, Temoignage $temoignage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $temoignage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($temoignage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('temoignage_index', [], Response::HTTP_SEE_OTHER);
    }
}
