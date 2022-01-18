<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sessions;
use App\Entity\Users;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $sessions = $this->getDoctrine()->getRepository(Sessions::class)->findBy(array('dateStart' => new \DateTime));

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'sessions' => $sessions,
        ]);
    }
}
