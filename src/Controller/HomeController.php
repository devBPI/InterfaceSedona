<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        return $this->render('home/default.html.twig', []);
    }

    /**
     * @Route("/auto-formation", name="home_autoformation")
     * @Route("/presse", name="home_presse")
     * @Route("/cinema", name="home_cinema")
     */
    public function thematicAction(Request $request)
    {
        return $this->render('home/thematic.html.twig', []);
    }
}
