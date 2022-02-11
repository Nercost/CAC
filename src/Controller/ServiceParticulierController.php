<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use App\Repository\ServicesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServiceParticulierController extends AbstractController
{
    #[Route('/service/particulier', name: 'service_particulier')]
    public function index(EntrepriseRepository $entrepriseRepository, ServicesRepository $servicesRepository): Response
    {
        return $this->render('service_particulier/index.html.twig', [
            'services' => $servicesRepository->triParticuliers(),
            'entreprise' => $entrepriseRepository->find(1),
        ]);
    }
}
