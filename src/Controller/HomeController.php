<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("", name="home")
     * @Route("/", name="home2")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return $this->render('home/default.html.twig', []);
    }

    /**
     * @Route("/accueille/auto-formation", name="home_autoformation")
     * @Route("/accueille/presse", name="home_presse")
     * @Route("/accueille/cinema", name="home_cinema")
     * @Method("GET")
     */
    public function thematicAction(Request $request)
    {
        return $this->render('home/thematic.html.twig', []);
    }
}
