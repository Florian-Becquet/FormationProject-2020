<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
     */
    public function index()
    {
        return $this->render('connexion/connexion.html.twig', [
            'controller_name' => 'ConnexionController',
        ]);
    }

    /** 
     * @Route("/deconnexion", name="deconnexion")
     */
    public function logout() {}
}
