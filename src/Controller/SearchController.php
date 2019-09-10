<?php


namespace App\Controller;

use App\Model\Exception\SearchHistoryException;
use App\Model\Search\Criteria;
use App\Model\Search\FacetFilter;
use App\Model\SuggestionList;
use App\Service\HistoricService;
use App\Service\Provider\AdvancedSearchProvider;
use App\Service\Provider\SearchProvider;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
     * @var SearchProvider
     */
    private $searchProvider;
    /**
     * @var AdvancedSearchProvider
     */
    private $advancedSearchProvider;
    /**
     * @var HistoricService
     */
    private $historicService;

    /**
     * SearchController constructor.
     * @param SearchProvider $searchProvider
     * @param AdvancedSearchProvider $advancedSearchProvider
     * @param HistoricService $historicService
     */
    public function __construct(
        SearchProvider $searchProvider,
        AdvancedSearchProvider $advancedSearchProvider,
        HistoricService $historicService
    ) {
        $this->searchProvider = $searchProvider;
        $this->advancedSearchProvider = $advancedSearchProvider;
        $this->historicService = $historicService;
    }

    /**
     * @Route("/recherche/{savedId}", defaults={"savedId" = null}, methods={"GET", "POST"}, name="search")
     * @param string $savedId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(string $savedId = null, Request $request)
    {
        $params = [];
        $title = '';
        $savedSearch = false;

        try {
            if ($savedId !== null && ($searchHistory = $this->historicService->getSearchHistoryByHash($savedId))) {
                $params = $searchHistory->getQueries();
                $title = $searchHistory->getTitle();
                $savedSearch = true;
            }
        } catch (SearchHistoryException $exception) {
            // log
        }

        if (!$savedSearch) {
            $params = $request->request->all();
            $title = $this->historicService->setTitleFromRequest($request);

            if ($request->request->count() > 0) {
                $this->historicService->saveMyHistoric($request);
            }
        }

        $criteria = new Criteria($params);
        $facets = new FacetFilter($params);
        $objSearch = $this->searchProvider->getListBySearch($criteria, $facets);

        return $this->render(
            'search/index.html.twig',
            [
                'title' => $title,
                'toolbar' => 'search',
                'objSearch' => $objSearch,
                'printRoute' => $this->generateUrl('search_pdf', ['format' => 'pdf']),
            ]
        );
    }

    /**
     * @Route("/recherche-tout", methods={"GET","HEAD"}, name="search_all")
     * @param Request $request
     * @return Response
     */
    public function searchAllAction(Request $request)
    {
        // TODO: controleur provisoire destiné a afficher une mise en page spécifique
        return $this->render(
            'search/index-all.html.twig',
            [
                'toolbar' => 'search',
                'printRoute' => $this->generateUrl('search_pdf', ['format' => 'pdf']),
            ]
        );
    }

    /**
     * @Route("/print/recherche.{format}", methods={"GET","HEAD"}, name="search_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     * @param Request $request
     * @param \Knp\Snappy\Pdf $knpSnappy
     * @param $format
     * @return PdfResponse|Response
     */
    public function printAction(Request $request, \Knp\Snappy\Pdf $knpSnappy, $format)
    {
        $content = $this->renderView(
            "search/index.".($format == 'txt' ? 'txt' : 'pdf').".twig",
            [
                'isPrintLong' => $request->get('print-type', 'print-long') == 'print-long',
                'includeImage' => $request->get('print-image', null) == 'print-image',
            ]
        );
        $filename = 'search-'.date('Y-m-d_h-i-s');

        if ($format == 'txt') {
            return new Response(
                $content, 200, [
                    'Content-Type' => 'application/force-download',
                    'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"',
                ]
            );
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
        return $this->render(
            'search/blocs-advanced-search/content.html.twig',
            [
                'criteria' => $this->advancedSearchProvider->getAdvancedSearchCriteria(),
                'queries' => $request->get(Criteria::QUERY_NAME, []),
                'filters' => $request->get(FacetFilter::QUERY_NAME, []),
            ]
        );
    }

    /**
     * @Route("/autocompletion", methods={"POST"}, name="search_autocompletion")
     * @param Request $request
     * @return JsonResponse
     */
    public function autocompletionAction(Request $request): JsonResponse
    {
        try {
            $query = $request->get('word');

            $objSearch = $this->searchProvider->findNoticeAutocomplete($query, SuggestionList::class);

            return new JsonResponse(
                [
                    'html' => $this->renderView(
                        'search/autocompletion.html.twig',
                        [
                            'words' => $objSearch->getSuggestions(),
                        ]
                    ),
                ]
            );
        } catch (\Exception $exception) {
            return new JsonResponse(
                [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]
            );
        }
    }

}
