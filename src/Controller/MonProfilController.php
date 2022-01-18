<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

class MonProfilController extends AbstractController
{

    /**
     * @Route("/profil", name="monProfil")
     */
    public function profil()
    {
        $idUser = $this->getUser()->getId();
        $users = $this->getDoctrine()->getRepository(Users::class)->findBy(array('id' => $idUser));

        return $this->render('mon_profil/profil.html.twig', [
            'controller_name' => 'MonProfilController',
            'users' => $users,
        ]);
    }

    /**
     * @Route("/profil/{id}/edit", name="modifProfil")
     */
    public function modifProfils(Users $user = null, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, $id)
    {

        //Permet de renvoyer sur une autre page si le user essaye de changer l'id dans l'url
        if ($this->getUser()->getId() != $id) {
            dump("ON PEUT PAS MODIF");
        }

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
            return $this->redirectToRoute('monProfil');
        }

        return $this->render('inscription/inscription.html.twig', [
            'form' => $form->createView(),
            'editMode' => $user->getId() !== null,
            'users' => $users
        ]);
    }
}
