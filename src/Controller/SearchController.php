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
     * @Route("/recherche-avancer", name="search")
     */
    public function advancedSearchAction(Request $request)
    {
        return $this->render('search/advanced-search.html.twig', []);
    }

    /**
     * @Route("/autocompletion", name="autocompletion")
     */
    public function autocompletionAction(Request $request)
    {
        return $this->render('search/autocompletion.html.twig', []);
    }

    /**
     * @Route("/notice-bibliographique", name="bibliographic-record")
     */
    public function bibliographicRecordAction(Request $request)
    {
        return $this->render('search/bibliographic-record.html.twig', []);
    }

    /**
     * @Route("/notice-autorite", name="authority-record")
     */
    public function authorityRecordAction(Request $request)
    {
        return $this->render('search/authority-record.html.twig', []);
    }

}
