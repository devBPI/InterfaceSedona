<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/donnees-personnelles", name="personal-data")
     */
    public function personalDataAction(Request $request)
    {
        return $this->render('user/personal-data.html.twig', []);
    }

    /**
     * @Route("/suggestion", name="suggestion")
     */
    public function suggestionAction(Request $request)
    {
        return $this->render('user/suggestion.twig', []);
    }




}
