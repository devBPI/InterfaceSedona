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
     * @var AdvancedSearchProvider
     */
    private $advancedSearchProvider;

    /**
     * SearchController constructor.
     * @param SearchProvider $searchProvider
     * @param AdvancedSearchProvider $advancedSearchProvider
     */
    public function __construct(SearchProvider $searchProvider, AdvancedSearchProvider $advancedSearchProvider)
    {
        $this->searchProvider = $searchProvider;
        $this->advancedSearchProvider = $advancedSearchProvider;
    }

    /**
     * @Route("/recherche", methods={"GET", "POST"}, name="search")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(Request $request)
    {
        $criteria = new Criteria($request);
        $facets = new FacetFilter($request);
        $objSearch = $this->searchProvider->getListBySearch($criteria, $facets);

        $title = 'page.search.title';
        $title .= $request->get(WordsList::ADVANCED_SEARCH_LABEL) === WordsList::CLICKED ?
            'advanced' : 'simple';

        return $this->render('search/index.html.twig', [
            'title'         => $title,
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function advancedSearchContent(Request $request)
    {
        return $this->render('search/blocs-advanced-search/content.html.twig', [
            'criteria' => $this->advancedSearchProvider->getAdvancedSearchCriteria(),
            'queries' => $request->get(Criteria::QUERY_NAME, []),
            'filters' => $request->get(FacetFilter::QUERY_NAME, [])
        ]);
    }

    /**
     * @Route("/autocompletion", methods={"POST"}, name="search_autocompletion")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function autocompletionAction(Request $request)
    {

        $query = $request->get('word');

        try{
            $objSearch = $this->searchProvider->findNoticeAutocomplete($query, SuggestionList::class);

        }catch(\Exception $exception){
            return new JsonResponse([
                'code'=> $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }

        return new JsonResponse([
            'html' => $this->renderView('search/autocompletion.html.twig', ['words' => $objSearch->getSuggestions(),
            ])
        ]);
    }

}
