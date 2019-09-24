<?php


namespace App\Controller;

use App\Model\Search\Criteria;
use App\Model\Search\FacetFilter;
use App\Model\Search\SearchQuery;
use App\Model\SuggestionList;
use App\Service\Provider\AdvancedSearchProvider;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\NoticeProvider;
use App\Service\Provider\SearchProvider;
use App\Utils\PrintNoticeWrapper;
use App\Service\SearchService;
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
     * @var SearchService
     */
    private $searchService;
    /**
     * @var NoticeProvider
     */
    private $noticeProvider;
    /**
     * @var NoticeAuthorityProvider
     */
    private $noticeAuhtority;

    /**
     * SearchController constructor.
     * @param SearchProvider $searchProvider
     * @param AdvancedSearchProvider $advancedSearchProvider
     * @param SearchService $searchService
     * @param NoticeProvider $noticeProvider
     * @param NoticeAuthorityProvider $noticeAuhtority
     */
    public function __construct(
        SearchProvider $searchProvider,
        AdvancedSearchProvider $advancedSearchProvider,
        SearchService $searchService,
        NoticeProvider $noticeProvider,
        NoticeAuthorityProvider $noticeAuhtority
    ) {
        $this->searchProvider = $searchProvider;
        $this->advancedSearchProvider = $advancedSearchProvider;
        $this->searchService = $searchService;
        $this->noticeProvider = $noticeProvider;
        $this->noticeAuhtority = $noticeAuhtority;
    }

    /**
     * @Route("/recherche", methods={"GET", "POST"}, name="search")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(Request $request): Response
    {
        $keyword = $request->get(Criteria::SIMPLE_SEARCH_KEYWORD, '');
        $criteria = new Criteria();
        $criteria->setSimpleSearch($request->get(Criteria::SIMPLE_SEARCH_TYPE), $keyword);

        return $this->displaySearch(new SearchQuery($criteria), $request);
    }

    /**
     * @param SearchQuery $search
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function displaySearch(SearchQuery $search, Request $request): Response
    {
        $objSearch = $this->searchService->createObjSearch($search, $request);
        $objSearch->setResults($this->searchProvider->getListBySearch($search));

        return $this->render(
            'search/index.html.twig',
            [
                'toolbar' => 'search',
                'objSearch' => $objSearch,
                'printRoute' => $this->generateUrl('search_pdf', ['format' => 'pdf']),
            ]
        );
    }

    /**
     * @Route("/recherche-avancee", methods={"GET", "POST"}, name="advanced_search")
     *
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function advancedSearchAction(Request $request): Response
    {
        $criteria = new Criteria();
        $criteria->setAdvancedSearch($request->query->all());

        return $this->displaySearch(new SearchQuery($criteria, new FacetFilter($request->query->all())), $request);
    }

    /**
     * @Route("/recherche-affinee", methods={"GET"}, name="refined_search")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function refinedSearchAction(Request $request): Response
    {
        $search = $this->searchService->getSearchQueryFromToken($request->get('searchToken'), $request);
        $search->setFacets(new FacetFilter($request->query->all()));

        return $this->displaySearch(
            $search,
            $request
        );
    }


    /**
     * @Route("/retour-recherche/{token}", methods={"GET"}, name="back_search")
     *
     * @param string $token
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function returnToSearchAction(string $token, Request $request): Response
    {
        return $this->displaySearch(
            $this->searchService->getSearchQueryFromToken($token, $request),
            $request
        );
    }

    /**
     * @Route("/recherche-sauvegardee/{savedId}", methods={"GET"}, name="saved_search")
     * @param string $savedId
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function savedSearchAction(string $savedId, Request $request)
    {
        return $this->displaySearch(
            $this->searchService->getSearchQueryFromHistoryId($savedId),
            $request
        );
    }

    /**
     * @Route("/recherche-tout", methods={"GET","HEAD"}, name="search_all")
     * @param Request $request
     * @return Response
     */
    public function searchAllAction(Request $request)
    {
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
        $authorities=[];
        $notices=[];
        parse_str(urldecode($request->get('authorities', null)),$authorities);
        parse_str(urldecode($request->get('notices', null)),$notices);
        $printNoticeWrapper = new PrintNoticeWrapper();
        $content = $this->renderView(
            "search/index.".($format == 'txt' ? 'txt' : 'pdf').".twig",
            [
                'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
                'includeImage'  => $request->get('print-image', null) == 'print-image',
                'printNoticeWrapper'=> $printNoticeWrapper($authorities+$notices, $this->noticeProvider, $this->noticeAuhtority)
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

            return new JsonResponse([
                'html' => $this->renderView(
                    'search/autocompletion.html.twig',
                    [
                        'words' => $objSearch->getSuggestions(),
                    ]
                ),
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }

}
