<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/recherche", methods={"GET","HEAD"}, name="search")
     */
    public function indexAction(Request $request)
    {
        return $this->render('search/index.html.twig', ['toolbar'=> 'search']);
    }

    /**
     * @Route("/recherche-tout", methods={"GET","HEAD"}, name="search_all")
     */
    public function searchAllAction(Request $request)
    {
        // TODO: controleur provisoire destiné a afficher une mise en page spécifique

        return $this->render('search/search-all.html.twig', ['toolbar'=> 'search']);
    }

    /**
     * @Route("/recherche-avance", methods={"GET","HEAD"}, name="search_advanced")
     */
    public function advancedSearchAction(Request $request)
    {
        return $this->render('search/modal/advanced-search.html.twig', []);
    }

    /**
     * @Route("/autocompletion", methods={"GET","HEAD"}, name="search_autocompletion")
     */
    public function autocompletionAction(Request $request)
    {
        return $this->render('search/autocompletion.html.twig', []);
    }

}
