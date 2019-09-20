<?php


namespace App\Controller;

use App\Model\Search;
use App\Model\Search\Criteria;
use App\Model\Search\FacetFilter;
use App\Model\SuggestionList;
use App\Service\Provider\AdvancedSearchProvider;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\NoticeProvider;
use App\Service\Provider\SearchProvider;
use App\Utils\PrintNoticeWrapper;
use App\WordsList;
use JMS\Serializer\SerializerInterface;
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

    /**
     * @var SearchProvider
     */
    private $searchProvider;
    /**
     * @var AdvancedSearchProvider
     */
    private $advancedSearchProvider;
    /**
     * @var SerializerInterface
     */
    private $serializer;
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
     * @param SerializerInterface $serializer
     * @param NoticeProvider $noticeProvider
     * @param NoticeAuthorityProvider $noticeAuhtority
     */
    public function __construct(
        SearchProvider $searchProvider,
        AdvancedSearchProvider $advancedSearchProvider,
        SerializerInterface $serializer,
        NoticeProvider $noticeProvider,
        NoticeAuthorityProvider $noticeAuhtority
    )
    {
        $this->searchProvider = $searchProvider;
        $this->advancedSearchProvider = $advancedSearchProvider;
        $this->serializer = $serializer;
        $this->noticeProvider = $noticeProvider;
        $this->noticeAuhtority = $noticeAuhtority;
    }

    /**
     * @Route("/recherche", methods={"GET", "POST"}, name="search")
     * 
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(Request $request, SessionInterface $session)
    {
        if ($request->get('searchToken')){
            $search = $this
                ->serializer
                ->deserialize(
                    $session->get($request->get('searchToken')),
                    Search::class,
                    'json'
                )
            ;
        }else{
            $search = new Search(new Criteria($request), new FacetFilter($request));
        }

        $search->getCriteria()->setSort($request->get('sort', Criteria::SORT_DEFAULT));
        $search->getCriteria()->setRows($request->get('rows', Criteria::ROWS_DEFAULT));
        $search->getCriteria()->setPage($request->get('page', 1));

        $objSearch  = $this->searchProvider->getListBySearch($search->getCriteria(), $search->getFacets());
        $title      = 'page.search.title';
        $title      .= $request->get(WordsList::ADVANCED_SEARCH_LABEL) === WordsList::CLICKED ?'advanced' : 'simple';

        $hash       = \spl_object_hash($search);

        $session->set($hash, $this->serializer->serialize($search, 'json'));

        return $this->render(
            'search/index.html.twig',
            [
                'title'         => $title,
                'toolbar'       => 'search',
                'objSearch'     => $objSearch,
                'printRoute'    => $this->generateUrl('search_pdf', ['format' => 'pdf']),
                'searchToken'   => $hash
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
