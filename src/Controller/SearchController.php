<?php


namespace App\Controller;

use App\Controller\Traits\PrintTrait;
use App\Entity\SearchHistory;
use App\Model\Form\ExportNotice;
use App\Model\Notice;
use App\Model\Search\Criteria;
use App\Model\Search\FacetFilter;
use App\Model\Search\ObjSearch;
use App\Model\Search\SearchQuery;
use App\Model\SuggestionList;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\SearchProvider;
use App\Service\SearchService;
use App\WordsList;
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
    use PrintTrait;

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
     * SearchController constructor.
     * @param SearchProvider $searchProvider
     * @param SearchService $searchService
     * @param NoticeBuildFileService $service
     */
    public function __construct(
        SearchProvider $searchProvider,
        SearchService $searchService,
        NoticeBuildFileService $service
    ) {
        $this->searchProvider = $searchProvider;
        $this->searchService = $searchService;
        $this->buildFileContent = $service;
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
            new SearchQuery($criteria, new FacetFilter($request->query->all()), SearchQuery::ADVANCED_MODE),
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
     * @Route("/retour-recherche/{searchToken}", methods={"GET"}, name="back_search")
     * @Route("/{parcours}/retour-recherche/{searchToken}", methods={"GET"}, name="back_search_parcours")
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

        return  $this->buildFileContent->buildFile($sendAttachement, ObjSearch::class, $format);
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
                'printRoute' => $this->generateUrl('search_pdf', ['format' => 'pdf']),
            ]
        );
    }

}
