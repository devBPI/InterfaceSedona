<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/recherche", name="search")
     */
    public function indexAction(Request $request)
    {
        return $this->render('search/index.html.twig', []);
    }

    /**
     * @Route("/recherche-avancer", name="search_advanced")
     */
    public function advancedSearchAction(Request $request)
    {
        return $this->render('search/advanced-search.html.twig', []);
    }

    /**
     * @Route("/autocompletion", name="search_autocompletion")
     */
    public function autocompletionAction(Request $request)
    {
        return $this->render('search/autocompletion.html.twig', []);
    }

}
