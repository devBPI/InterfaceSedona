<?php


namespace App\Controller;

use App\Model\Search\Criteria;
use App\Model\Search\FacetFilter;
use App\Model\Search\ObjSearch;
use App\Model\Search\SearchQuery;
use App\Service\Provider\AdvancedSearchProvider;
use App\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class AdvancedSearchController
 * @package App\Controller
 */
final class AdvancedSearchController extends AbstractController
{
    /**
     * @var AdvancedSearchProvider
     */
    private $advancedSearchProvider;
    /**
     * @var SearchService
     */
    private $searchService;

    /**
     * AdvancedSearchController constructor.
     * @param AdvancedSearchProvider $advancedSearchProvider
     * @param SearchService $searchService
     */
    public function __construct(AdvancedSearchProvider $advancedSearchProvider, SearchService $searchService)
    {
        $this->advancedSearchProvider = $advancedSearchProvider;
        $this->searchService = $searchService;
    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function index(Request $request, SessionInterface $session): Response
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
                'objSearch' => $objSearch,
                'modeDate' => $request->get('adv-search-date')
            ]
        );
    }


}