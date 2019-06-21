<?php


namespace App\Controller;


use App\Service\Provider\SearchProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller
 */
class SearchController extends AbstractController
{
    /**
     * @var SearchProvider
     */
    private $searchProvider;

    /**
     * SearchController constructor.
     * @param SearchProvider $searchProvider
     */
    public function __construct(SearchProvider $searchProvider)
    {
        $this->searchProvider = $searchProvider;
    }

    /**
     * @Route("/recherche", methods={"GET","HEAD"}, name="search")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $objSearch = $this->searchProvider->getListBySearch($request->get('search', ''));
var_dump($objSearch->getAuthors());

die('end');
        return $this->render('search/index.html.twig', [
            'toolbar'=> 'search',
            'objSearch' => $objSearch
        ]);
    }

    /**
     * @Route("/recherche-avance", methods={"GET","HEAD"}, name="search_advanced")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function advancedSearchAction(Request $request)
    {
        return $this->render('search/modal/advanced-search.html.twig', []);
    }

    /**
     * @Route("/autocompletion", methods={"GET","HEAD"}, name="search_autocompletion")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function autocompletionAction(Request $request)
    {
        return $this->render('search/autocompletion.html.twig', []);
    }

}
