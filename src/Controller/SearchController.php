<?php


namespace App\Controller;

use App\Controller\Traits\ObjSearchInstanceTrait;
use App\Controller\Traits\PrintTrait;
use App\Entity\SearchHistory;
use App\Model\Form\ExportNotice;
use App\Model\Notice;
use App\Model\Search\Criteria;
use App\Model\Search\FilterFilter;
use App\Model\Search\FacetFilter;
use App\Model\Search\ListNavigation;
use App\Model\Search\ObjSearch;
use App\Model\Search\SearchQuery;
use App\Model\SuggestionList;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\SearchProvider;
use App\Service\SearchService;
use App\WordsList;
use Doctrine\ORM\EntityManagerInterface;
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
final class SearchController extends AbstractController
{
    use PrintTrait, ObjSearchInstanceTrait;

    public const GENERAL ='general';

    /**
     * @var SearchProvider
     */
    private $searchProvider;
    /**
     * @var SearchService
     */
    private $searchService;

    /**
     * @var NoticeBuildFileService
     */
    private $buildFileContent;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    /**
     * SearchController constructor.
     * @param EntityManagerInterface $entityManager
     * @param SearchProvider $searchProvider
     * @param SearchService $searchService
     * @param NoticeBuildFileService $service
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SearchProvider $searchProvider,
        SearchService $searchService,
        NoticeBuildFileService $service
    ) {
        $this->searchProvider = $searchProvider;
        $this->searchService = $searchService;
        $this->buildFileContent = $service;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/{parcours}/recherche-simple", methods={"GET", "POST"}, name="search_parcours")
     * @Route("/recherche-simple", methods={"GET", "POST"}, name="search")
     *
     * @param Request $request
     * @param string $parcours
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(Request $request, string $parcours=self::GENERAL): Response
    {
        $keyword = $request->get(Criteria::SIMPLE_SEARCH_KEYWORD, '');
        $type = $request->get(Criteria::SIMPLE_SEARCH_TYPE, WordsList::THEME_DEFAULT);

        $criteria = new Criteria();
        $criteria->setSimpleSearch($type, $keyword);
        $criteria->setParcours($parcours);

        return $this->displaySearch(new SearchQuery($criteria), $request);
    }
    /**
     * @Route("/{parcours}/resultats/essentiels/{essentiels}", methods={"GET", "POST"}, name="advanced_search_parcours_essentiels")
     * @Route("/{parcours}/resultats/essentiels/{essentiel1}/{essentiel2}", methods={"GET"}, name="search_parcours_essentiels_with_slash")

     * @param Request $request
     * @param string $parcours
     * @param string $essentiels
     * @param string $essentiel1
     * @param string $essentiel2
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function advancedSearchWithEssentielsAction(Request $request, string $parcours=self::GENERAL,string $essentiels='',string $essentiel1='', string $essentiel2=''): Response
    {
        $request->request->set('essentiels', $essentiels);
        if($essentiel1!==''){
            $request->request->set('essentiels', $essentiel1. '/'.$essentiel2);
        }
        $criteria = new Criteria();
        $criteria->setParcours($parcours);

        if ($request->get('search-type', 'simple') === 'advanced'){
            $criteria->setAdvancedSearch($request->query->all());

            return $this->displaySearch(
                new SearchQuery($criteria, new FilterFilter($request->query->all()), new FacetFilter($request->query->all()), SearchQuery::ADVANCED_MODE),
                $request
            );
        }
            $keyword = $request->get(Criteria::SIMPLE_SEARCH_KEYWORD, '');
            $type = $request->get(Criteria::SIMPLE_SEARCH_TYPE, WordsList::THEME_DEFAULT);
            $criteria->setSimpleSearch($type, $keyword);

            return $this->displaySearch(new SearchQuery($criteria), $request);
    }


    /**
     * @Route("/{parcours}/recherche-avancee", methods={"GET", "POST"}, name="advanced_search_parcours")
     * @Route("/recherche-avancee", methods={"GET", "POST"}, name="advanced_search")
     *
     * @param Request $request
     * @param string $parcours
     * @return Response
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
            new SearchQuery($criteria, new FilterFilter($request->query->all()), new FacetFilter($request->query->all()), SearchQuery::ADVANCED_MODE),
            $request
        );
    }

    /**
     * @Route("/recherche-affinee", methods={"GET"}, name="refined_search")
     * @Route("/{parcours}/recherche-affinee", methods={"GET"}, name="refined_search_parcours")
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
    public function refinedSearchAction(Request $request, string $parcours=self::GENERAL): Response
    {
        if (!$request->getSession()->get($request->get(ObjSearch::PARAM_REQUEST_NAME, null))){
            return $this->redirect(
                $this->generateUrl('search_parcours', ['parcours' => $parcours])
            );
        }
        $search = $this->searchService->getSearchQueryFromToken($request->get(ObjSearch::PARAM_REQUEST_NAME, null), $request);

        $criteria = $search->getCriteria()->setParcours($parcours);
        $search
            ->setFacets(new FacetFilter($request->query->all()))
            ->setCriteria($criteria)
        ;

        return $this->displaySearch(
            $search,
            $request
        );
    }

    /**
     * @Route("/recherche-retour/{searchToken}", methods={"GET"}, name="back_search")
     * @Route("/{parcours}/recherche-retour/{searchToken}", methods={"GET"}, name="back_search_parcours")
     *
     * @param string $searchToken
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function returnToSearchAction(string $searchToken, Request $request): Response
    {
        return $this->displaySearch(
            $this->searchService->getSearchQueryFromToken($searchToken, $request),
            $request
        );
    }

    /**
     * @Route("/recherche-sauvegardee/{id}", methods={"GET"}, name="saved_search")
     *
     * @param SearchHistory $searchHistory
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function savedSearchAction(SearchHistory $searchHistory, Request $request): Response
    {
        if ($searchHistory->getParcours()){
            /**
             * we add a supplemantary field to save "parkours" because it's lost in creteria
             */
            $request->request->set('parcours', $searchHistory->getParcours());
        }
        return $this->displaySearch(
            $this->searchService->deserializeSearchQuery($searchHistory->getQueryString()),
            $request
        );
    }

    /**
     * @Route("/print/recherche.{format}", methods={"GET","HEAD"}, name="search_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     *
     * @param Request $request
     * @param string $format
     * @return PdfResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function printAction(Request $request,string $format='pdf')
    {
        $sendAttachement = (new ExportNotice())
            ->setAuthorities($request->get('authorities', ''))
            ->setNotices($request->get('notices', ''))
            ->setIndices($request->get('indices', ''))
            ->setImage($request->get('print-image', null) === 'print-image')
            ->setFormatType($format)
            ->setShortFormat($request->get('print-type', 'print-long') !== 'print-long')
      ;

        return  $this->buildFileContent->buildFile($sendAttachement, ObjSearch::class);
    }

    /**
     * @Route("/autocompletion", methods={"GET"}, name="search_autocompletion")
     * @Route("/{parcours}/autocompletion", methods={"GET"}, name="search_autocompletion_parcours")
     * @param Request $request
     * @return JsonResponse
     */
    public function autocompletionAction(Request $request, string $parcours=self::GENERAL): JsonResponse
    {
        try {
            $type = $request->get('type', WordsList::THEME_DEFAULT);
            $word = $request->get('word');
            $mode = $request->get('mode');
            $objSearch = $this->searchProvider->findNoticeAutocomplete($type, $word, SuggestionList::class);

            return new JsonResponse([
                'html' => $this->renderView(
                    'search/blocs-searchextended/autocompletion.html.twig',
                    [
                        'words' => $objSearch->getSuggestions(),
                        'type'  => $type,
                        'mode'  => $mode
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
     * @param SearchQuery $search
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function displaySearch(SearchQuery $search, Request $request): Response
    {
        $objSearch = $this->searchService->createObjSearch($search, $request);
        $objSearch->setResults($this->searchProvider->getListBySearch($search));
        $request->query->remove('action');
        $objSearchBis = new ObjSearch($this->getObjSearchQuery($request));

        $request->getSession()->set(NavigationService::SESSION_KEY, serialize(new ListNavigation($objSearch)));
        $request->getSession()->set('searchToken', serialize($objSearch));

        $seeAll = $request->get('see-all', Notice::ALL);
        $template = 'search/index.html.twig';
        if (in_array($seeAll, [Notice::SEE_ONLINE, Notice::SEE_ONSHELF] )){
            $template = 'search/index-all.html.twig';
        }

        return $this->render(
            $template,
            [
                'toolbar'   => ObjSearch::class,
                'seeAll'    => $seeAll,
                'objSearch' => $objSearch,
                'objSearchBis'=>$objSearchBis,
                'printRoute' => $this->generateUrl('search_pdf', ['format' => 'pdf']),
            ]
        );
    }
}
