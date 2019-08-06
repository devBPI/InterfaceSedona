<?php


namespace App\Controller;

use Spipu\Html2Pdf\Html2Pdf;
use App\Service\Provider\SearchProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller
 */
class SearchController extends AbstractController
{
    use PrintTrait;

    public const QUERY_LABEL = 'search';

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
        $query = $request->get(self::QUERY_LABEL, '');
        $objSearch = $this->searchProvider->getListBySearch($query);
        $objSearch->setQuery($query);

        return $this->render('search/index.html.twig', [
            'toolbar'       => 'search',
            'objSearch'     => $objSearch,
            'printRoute'    => $this->generateUrl('search_pdf')
        ]);
    }

    /**
     * @Route("/recherche-tout", methods={"GET","HEAD"}, name="search_all")
     */
    public function searchAllAction(Request $request)
    {
        // TODO: controleur provisoire destiné a afficher une mise en page spécifique
        return $this->render('search/index-all.html.twig', [
            'toolbar'       => 'search',
            'printRoute'    => $this->generateUrl('search_pdf')
        ]);
    }

    /**
     * @Route("/recherche.{format}", methods={"GET","HEAD"}, name="search_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function printAction(Request $request, $format)
    {
        $content = $this->renderView("search/index.".($format == 'txt' ? 'txt': 'pdf').".twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
        ]);

        return $this->renderPrint($content,'search'.date('Y-m-d_h-i-s'), $format );
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
