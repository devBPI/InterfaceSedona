<?php


namespace App\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
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
            'printRoute'    => $this->generateUrl('search_pdf',['format'=> 'pdf'])
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
            'printRoute'    => $this->generateUrl('search_pdf',['format'=> 'pdf'])
        ]);
    }

    /**
     * @Route("/print/recherche.{format}", methods={"GET","HEAD"}, name="search_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function printAction(Request $request, \Knp\Snappy\Pdf $knpSnappy, $format)
    {
        $content = $this->renderView("search/index.".($format == 'txt' ? 'txt': 'pdf').".twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
        ]);
        $filename = 'search-'.date('Y-m-d_h-i-s');

        if ($format == 'txt') {
            return new Response($content,200,[
                'Content-Type' => 'application/force-download',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"'
            ]);
        } elseif ($format == 'html') {
            return new Response($content);
        }

        return new PdfResponse(
            $knpSnappy->getOutputFromHtml($content),
            $filename.".pdf"
        );
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
     * @Route("/autocompletion", methods={"POST"}, name="search_autocompletion")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function autocompletionAction(Request $request)
    {

        $query = $request->get('word');
        $objSearch = $this->searchProvider->findNoticeAutocomplete($query);
        //$facet = $objSearch->getFacets();

        dump($objSearch); die;

        return new JsonResponse([
            'code' => $objSearch->getStatusCode(),
            'message' => $serviceProposed->message,
            'html' => $this->renderView('        search/autocompletion.html.twig', ['words' => $objSearch,
            ])

        ]);


    }

}
