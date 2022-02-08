<?php

namespace App\Controller\admin;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\DescriptionRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entreprise')]
class EntrepriseController extends AbstractController
{
    #[Route('/{id}/edit', name: 'entreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager, DescriptionRepository $descriptionRepository, EntrepriseRepository $entrepriseRepository): Response
    {
        $old_name_image = $entreprise->getImage();
        $path = $this->getParameter('upload_dir') . $old_name_image;
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                if (file_exists($path)) {
                    unlink($path);
                }

                $image_new_name = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('upload_dir'), $image_new_name);
                $entreprise->setImage($image_new_name);
            } else {
                $entreprise->setImage($old_name_image);
            }
            $entityManager->flush();

            return $this->redirectToRoute('entreprise_edit', ['id' => 1], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entreprise/edit.html.twig', [
            'entreprise' => $entrepriseRepository->find(1),
            'form' => $form,
            'descriptions' => $descriptionRepository->triPosition(),
        ]);
    }
}
