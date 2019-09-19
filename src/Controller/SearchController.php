<?php


namespace App\Controller;

use App\Entity\SearchHistory;
use App\Model\Exception\SearchHistoryException;
use App\Model\Search;
use App\Model\Search\Criteria;
use App\Model\Search\FacetFilter;
use App\Model\SuggestionList;
use App\Service\HistoricService;
use App\Service\Provider\AdvancedSearchProvider;
use App\Service\Provider\SearchProvider;
use App\WordsList;
use JMS\Serializer\SerializerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller
 */
class SearchController extends AbstractController
{
    const INPUT_AUTHOR = 'authors';
    const INPUT_NOTICE = 'notices';

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
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var SessionStorageInterface
     */
    private $sessionStorage;

    /**
     * SearchController constructor.
     * @param SearchProvider $searchProvider
     * @param AdvancedSearchProvider $advancedSearchProvider
     * @param HistoricService $historicService
     * @param SerializerInterface $serializer
     * @param SessionStorageInterface $sessionStorage
     */
    public function __construct(
        SearchProvider $searchProvider,
        AdvancedSearchProvider $advancedSearchProvider,
        HistoricService $historicService,
        SerializerInterface $serializer,
        SessionStorageInterface $sessionStorage
    ) {
        $this->searchProvider = $searchProvider;
        $this->advancedSearchProvider = $advancedSearchProvider;
        $this->historicService = $historicService;
        $this->serializer = $serializer;
        $this->sessionStorage = $sessionStorage;
    }

    /**
     * @Route("/recherche/{token}", defaults={"token"=null}, methods={"GET", "POST"}, name="search")
     *
     * @param string $token
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(string $token = null, Request $request)
    {
        if ($token !== null && $request->getSession()->get($token)) {
            $search = $this->serializer->deserialize(
                $request->getSession()->get($token),
                Search::class,
                'json'
            );

            foreach ($request->query->all() as $query => $value) {
                $search->getFacets()->set($query, $value);
            }
        } else {
            $params = $request->query->all();
            $title = $this->historicService->setTitleFromRequest($request);

            $this->historicService->saveMyHistoric($request);


            $search = new Search($title, new Criteria($params), new FacetFilter($params));
        }
        $search->setSort($request->get('sort', Criteria::SORT_DEFAULT));
        $search->setRows($request->get('rows', Criteria::ROWS_DEFAULT));
        $search->setPage($request->get('page', 1));


        $hash = \spl_object_hash($search);
        $request->getSession()->set($hash, $this->serializer->serialize($search, 'json'));

        return $this->displaySearch($search, $hash);
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
        $searchHistory = $this->historicService->getSearchHistoryByHash($savedId);
        $params = $searchHistory->getQueries();
        $title = $searchHistory->getTitle();

        $search = new Search($title, new Criteria($params), new FacetFilter($params));
        $hash       = \spl_object_hash($search);
        $request->getSession()->set($hash, $this->serializer->serialize($search, 'json'));

        return $this->displaySearch($search, $hash);
    }


//    /**
//     * @Route("/retour-recherche/{token}", methods={"GET"}, name="back_search")
//     * @param string $token
//     * @param SessionInterface $session
//     * @return Response
//     * @throws \Twig\Error\LoaderError
//     * @throws \Twig\Error\RuntimeError
//     * @throws \Twig\Error\SyntaxError
//     */
//    public function returnToSearchAction(string $token, SessionInterface $session): Response
//    {
//        return $this->displaySearch(
//
//        );
//    }

    /**
     * @param Search $search
     * @param string $title
     * @param string $hash
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function displaySearch(Search $search, string $hash): Response
    {
        $objSearch = $this->searchProvider->getListBySearch($search);

        return $this->render(
            'search/index.html.twig',
            [
                'search' => $search,
                'title' => $search->getTitle(),
                'toolbar' => 'search',
                'objSearch' => $objSearch,
                'printRoute' => $this->generateUrl('search_pdf', ['format' => 'pdf']),
                'searchToken' => $hash
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
