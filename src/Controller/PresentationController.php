<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use App\Repository\DescriptionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PresentationController extends AbstractController
{
    #[Route('/presentation', name: 'presentation')]
    public function index(EntrepriseRepository $entrepriseRepository, DescriptionRepository $descriptionRepository): Response
    {

        return $this->render('presentation/index.html.twig', [
            'entreprise' => $entrepriseRepository->findAll(),
            'description' => $descriptionRepository->findAll(),
        ]);
    }
}