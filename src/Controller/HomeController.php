<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/accueil/autoformation", methods={"GET","HEAD"}, name="home_autoformation")
     * @Route("/accueil/presse", methods={"GET","HEAD"}, name="home_presse")
     * @Route("/accueil/cinema", methods={"GET","HEAD"}, name="home_cinema")
     */
    public function thematicAction(Request $request)
    {
        $theme = substr($request->getPathInfo(), strrpos($request->getPathInfo(), '/') + 1);
        return $this->render('home/thematic.html.twig', [
            'title' => $theme
        ]);
    }

    /**
     * @Route("/test", methods={"GET","HEAD"})
     */
    public function testAction(Request $request)
    {
        return new Response('test test test');
    }
}
