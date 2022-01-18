<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

            // Ici nous enverrons le mail
            $message = (new \Swift_Message('Nouveau Contact'))
                // On attribue l'expéditeur
                ->setFrom($contact['mail'])
                // On attribue le destinataire
                ->setTo('ovsuer@gmail.com')
                // On crée le message avec la vue Twig
                ->setBody(
                    $this->renderView(
                        'mails/contact.html.twig', compact('contact')
                    ),
                    'text/html'
                );
                // On envoie le message
                $mailer->send($message);

                $this->addFlash('message', 'Le message a bien été envoyé');
                return $this->redirectToRoute('index');
        }
        
        return $this->render('contact/contacter.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
