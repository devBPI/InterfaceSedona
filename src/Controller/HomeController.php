<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("", methods={"GET","HEAD"}, name="home")
     * @Route("/", methods={"GET","HEAD"}, name="home2")
     */
    public function indexAction(Request $request)
    {
        return $this->render('home/default.html.twig', []);
    }

    /**
     * @Route("/accueil/auto-formation", methods={"GET","HEAD"}, name="home_autoformation")
     * @Route("/accueil/presse", methods={"GET","HEAD"}, name="home_presse")
     * @Route("/accueil/cinema", methods={"GET","HEAD"}, name="home_cinema")
     */
    public function thematicAction(Request $request)
    {
        return $this->render('home/thematic.html.twig', []);
    }
}
