<?php

namespace App\Controller;

use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Form\ResertPassType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class InscriptionController extends AbstractController
{


    /**
     * @Route("/inscription", name="inscription")
     * @Route("/inscription/{id}/edit", name="modifProfil")
     */
    public function registration(Users $user = null, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {

        if (!$user) {
            $user = new Users();
        }
        $user->setDateInscription(new \DateTime());
        $users = $this->getDoctrine()->getRepository(Users::class)->findAll();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('connexion');
        }
        return $this->render('inscription/inscription.html.twig', [
            'form' => $form->createView(),
            'editMode' => $user->getId() !== null,
            'users' => $users
        ]);
    }

    /**
     * @Route("/oubli-pass", name="app_forgotten_password")
     */
    public function oubliPass(Request $request, UsersRepository $users, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        // On initialise le formulaire
        $form = $this->createForm(ResertPassType::class);

        // On traite le formulaire
        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On r??cup??re les donn??es
            $donnees = $form->getData();

            // On cherche un utilisateur ayant cet e-mail
            $user = $users->findOneByMail($donnees['mail']);

            // Si l'utilisateur n'existe pas
            if ($user === null) {
                // On envoie une alerte disant que l'adresse e-mail est inconnue
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
                
                // On retourne sur la page de connexion
                return $this->redirectToRoute('connexion');
            }

            // On g??n??re un token
            $token = $tokenGenerator->generateToken();

            // On essaie d'??crire le token en base de donn??es
            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('connexion');
            }

            // On g??n??re l'URL de r??initialisation de mot de passe
            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            // On g??n??re l'e-mail
            $message = (new \Swift_Message('Mot de passe oubli??'))
                ->setFrom('votre@adresse.fr')
                ->setTo($user->getMail())
                ->setBody(
                    "<p>Bonjour,</p><p>Une demande de r??initialisation de mot de passe a ??t?? effectu??e pour le site OnVaSuer.fr. Veuillez cliquer sur le lien suivant : " . $url .'</p>',
                    'text/html'
                )
            ;

            // On envoie l'e-mail
            $mailer->send($message);

            // On cr??e le message flash de confirmation
            $this->addFlash('message', 'E-mail de r??initialisation du mot de passe envoy?? !');

            // On redirige vers la page de login
            return $this->redirectToRoute('connexion');
        }

        // On envoie le formulaire ?? la vue
        return $this->render('security/forgotten_password.html.twig',['mailForm' => $form->createView()]);
    }





    /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        // On cherche un utilisateur avec le token donn??
        $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['reset_token' => $token]);

        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('connexion');
        }

        // Si le formulaire est envoy?? en m??thode post
        if ($request->isMethod('POST')) {
            // On supprime le token
            $user->setResetToken(null);

            // On chiffre le mot de passe
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

            // On stocke
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // On cr??e le message flash
            $this->addFlash('message', 'Mot de passe mis ?? jour');

            // On redirige vers la page de connexion
            return $this->redirectToRoute('connexion');
        }else {
            // Si on n'a pas re??u les donn??es, on affiche le formulaire
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }

}
