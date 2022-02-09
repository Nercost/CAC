<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{

    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer, EntrepriseRepository $entrepriseRepository)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();

            $message = (new Email())
                ->from($contactFormData['email'])
                ->to('reggiectout@gmail.com')
                ->subject('vous avez reçu un email')
                ->text(
                    'Sender : ' . $contactFormData['email'] . \PHP_EOL .
                        $contactFormData['Message'],
                    'text/plain'
                );
            $mailer->send($message);
            $this->addFlash('success', 'Vore message a été envoyé');
            return $this->redirectToRoute('contact');
        }
        return $this->render('contact/index.html.twig', [
            'our_form' => $form->createView(),
            'entreprise' => $entrepriseRepository->find(1),
        ]);
    }
}
