<?php


namespace App\Controller;

use App\Controller\Traits\PrintTrait;
use App\Entity\SearchHistory;
use App\Model\Form\ExportNotice;
use App\Model\Search\Criteria;
use App\Model\Search\FacetFilter;
use App\Model\Search\ObjSearch;
use App\Model\Search\SearchQuery;
use App\Model\SuggestionList;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\AdvancedSearchProvider;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\SearchProvider;
use App\Service\SearchService;
use App\WordsList;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller
 */
class SearchController extends AbstractController
{
    use PrintTrait;

    public const GENERAL ='general';
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
     * @var NoticeBuildFileService
     */
    private $buildFileContent;

    /**
     * SearchController constructor.
     * @param SearchProvider $searchProvider
     * @param AdvancedSearchProvider $advancedSearchProvider
     * @param SearchService $searchService
     * @param NoticeBuildFileService $service
     */
    public function __construct(
        SearchProvider $searchProvider,
        AdvancedSearchProvider $advancedSearchProvider,
        SearchService $searchService,
        NoticeBuildFileService $service
    ) {
        $this->searchProvider = $searchProvider;
        $this->advancedSearchProvider = $advancedSearchProvider;
        $this->searchService = $searchService;
        $this->buildFileContent = $service;
    }

    /**
     * @Route("/recherche/{parcours}", methods={"GET", "POST"}, name="search")
     *
     * @param Request $request
     * @param string $parcours
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(Request $request, string $parcours=self::GENERAL): Response
    {

        $keyword = $request->get(Criteria::SIMPLE_SEARCH_KEYWORD, '');
        $criteria = new Criteria();
        $criteria->setSimpleSearch($request->get(Criteria::SIMPLE_SEARCH_TYPE), $keyword);
        $criteria->setParcours($parcours);

        return $this->displaySearch(new SearchQuery($criteria), $request);
    }


    /**
     * @Route("/recherche-avancee/{parcours}", methods={"GET", "POST"}, name="advanced_search")
     *
     * @param Request $request
     * @param string $parcours
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function advancedSearchAction(Request $request, string $parcours=self::GENERAL): Response
    {

        $criteria = new Criteria();

        $criteria->setAdvancedSearch($request->query->all());
        $criteria->setParcours($parcours);
        return $this->displaySearch(
            new SearchQuery($criteria, new FacetFilter($request->query->all()), SearchQuery::ADVANCED_MODE),
            $request
        );
    }

    /**
     * @Route("/recherche-affinee/{parcours}", methods={"GET"}, name="refined_search")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function refinedSearchAction(Request $request, string $parcours=self::GENERAL): Response
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
     * @Route("/recherche-sauvegardee/{id}", methods={"GET"}, name="saved_search")
     *
     * @param SearchHistory $searchHistory
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function savedSearchAction(SearchHistory $searchHistory, Request $request): Response
    {
        return $this->displaySearch(
            $this->searchService->deserializeSearchQuery($searchHistory->getQueryString()),
            $request
        );
    }

    /**
     * @Route("/recherche-tout", methods={"GET","HEAD"}, name="search_all")
     * @param Request $request
     * @return Response
     */
    public function searchAllAction(Request $request): Response
    {
        return $this->render(
            'search/index-all.html.twig',
            [
                'toolbar' => ObjSearch::class,
                'printRoute' => $this->generateUrl('search_pdf', ['format' => 'pdf']),
            ]
        );
    }

    /**
     * @Route("/print/recherche.{format}", methods={"GET","HEAD"}, name="search_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     *
     * @param Request $request
     * @param $format
     * @return PdfResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function printAction(Request $request, $format)
    {
        $sendAttachement = new ExportNotice();
        $sendAttachement
            ->setAuthorities($request->get('authorities', ''))
            ->setNotices($request->get('notices', ''))
            ->setImage($request->get('print-image', null) === 'print-image')
            ->setFormatType($format)
            ->setShortFormat($request->get('print-type', 'print-long') !== 'print-long')
      ;
      return  $this->buildFileContent->buildFile($sendAttachement, ObjSearch::class, $format);

    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function advancedSearchContent(Request $request, SessionInterface $session): Response
    {
        if ($request->get('searchToken') !== null && $session->has($request->get('searchToken'))) {
            $searchQuery = $this->searchService->getSearchQueryFromToken($request->get('searchToken'), $request);
        } else {
            $criteria = new Criteria();
            $criteria->setAdvancedSearch($request->query->all());
            $searchQuery = new SearchQuery($criteria, new FacetFilter($request->query->all()));
        }

        $searchQuery->getCriteria()->setAdvancedSearch($request->query->all());
        $objSearch = new ObjSearch($searchQuery);

        return $this->render(
            'search/blocs-advanced-search/content.html.twig',
            [
                'criteria' => $this->advancedSearchProvider->getAdvancedSearchCriteria(),
                'objSearch' => $objSearch
            ]
        );
    }

    /**
     * @Route("/autocompletion", methods={"GET"}, name="search_autocompletion")
     * @param Request $request
     * @return JsonResponse
     */
    public function autocompletionAction(Request $request): JsonResponse
    {
        try {
            $type = $request->get('type', WordsList::THEME_DEFAULT);
            $word = $request->get('word');
            $objSearch = $this->searchProvider->findNoticeAutocomplete($type, $word, SuggestionList::class);

            return new JsonResponse([
                'html' => $this->renderView(
                    'search/autocompletion.html.twig',
                    [
                        'words' => $objSearch->getSuggestions(),
                        'type'  => $type
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

    /**
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

        $request->query->remove('action');

        return $this->render(
            'search/index.html.twig',
            [
                'toolbar' => ObjSearch::class,
                'seeAll'=> $request->get('see-all', 'all'),
                'objSearch' => $objSearch,
                'printRoute' => $this->generateUrl('search_pdf', ['format' => 'pdf']),
            ]
        );
    }

}
