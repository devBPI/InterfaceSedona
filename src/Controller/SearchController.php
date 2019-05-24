<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/recherche", name="search")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return $this->render('search/index.html.twig', ['toolbar'=> 'search']);
    }

    /**
     * @Route("/recherche-avancer", name="search_advanced")
     * @Method("GET")
     */
    public function advancedSearchAction(Request $request)
    {
        return $this->render('search/advanced-search.html.twig', []);
    }

    /**
     * @Route("/autocompletion", name="search_autocompletion")
     * @Method("GET")
     */
    public function autocompletionAction(Request $request)
    {
        return $this->render('search/autocompletion.html.twig', []);
    }

}
