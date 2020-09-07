<?php
declare(strict_types=1);

namespace App\Service;

use App\Controller\SearchController;
use App\Entity\SearchHistory;
use App\Model\Interfaces\RecordInterface;
use App\Model\NoticeThemed;
use App\Model\Search\Criteria;
use App\Model\Search\FilterFilter;
use App\Model\Search\FacetFilter;
use App\Model\Search\ObjSearch;
use App\Model\Search\SearchQuery;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BreadCrumbBuilder
 * @package App\Service
 */
final class BreadCrumbBuilder
{
    const RECORD = 'record';
    const SEARCH = 'search';
    const HELP = 'help';
    const USER = 'user';
    const HOME = 'home';


    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var BreadCrumbTrailService
     */
    private $bctService;

    /**
     * @var SearchService
     */
    private $searchService;

    /**
     * BreadCrumbBuilder constructor.
     * @param EntityManager $entityManager
     * @param BreadCrumbTrailService $bctService
     * @param SearchService $searchService
     */
    public function __construct(
        EntityManager $entityManager,
        BreadCrumbTrailService $bctService,
        SearchService $searchService
    ) {
        $this->entityManager = $entityManager;
        $this->bctService = $bctService;
        $this->searchService = $searchService;
    }

    /**
     * @return BreadCrumbTrailService
     */
    public function getBctService(): BreadCrumbTrailService
    {
        return $this->bctService;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function build(Request $request): bool
    {
        $route = $request->get('_route');

        if ($route !== null) {
            switch (true) {

                case strpos($route, self::USER) !== false:
                    return $this->buildForUserPage($request);
                case strpos($route, self::HELP) !== false:
                    return $this->buildForHelpPage($request);
                case strpos($route, self::HOME) !== false && $route !== 'home2':
                    return $this->buildForHome($request);
                case strpos($route, self::RECORD) !== false:
                    return $this->buildForCardPage($request);
                case strpos($route, self::SEARCH) !== false:
                    return $this->buildForSearchPage($request);
                default:
                    return false;
            }
        }

        return false;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function buildForUserPage(Request $request)
    {
        $route = $request->get('_route');
        $this->bctService->add($route, sprintf('breadcrumb.user.%s', $route));

        return true;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function buildForHelpPage(Request $request): bool
    {
        $route = $request->get('_route');
        $this->bctService->add('help_search', 'breadcrumb.help_index');
        $this->bctService->add($route, sprintf('breadcrumb.help.%s', $route));

        return true;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function buildForHome(Request $request): bool
    {
        $parcours = $request->get('parcours');

        $this->bctService->add(
            'home_thematic',
            sprintf('breadcrumb.home.%s', $parcours),
            ['parcours' => $parcours]
        );

        return true;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function buildForCardPage(Request $request): bool
    {
        $notice = $request->get('notice');
        $this->buildForSearchPage($request);
        if ($notice instanceof NoticeThemed) {
            $notice = $notice->getNotice();
        }

        if ($notice instanceof RecordInterface) {
            $this->bctService->add(
                sprintf('record_%s', $notice->getBreadcrumbName()),
                sprintf('breadcrumb.card.%s', $notice->getBreadcrumbName()),
                [
                    'permalink' => $notice->getPermalink(),
                    'parcours' => $request->get('parcours', SearchController::GENERAL),
                ],
                ['%title%' => $notice->getTitle()]
            );
        }

        return true;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function buildForSearchPage(Request $request): bool
    {
        $route = $request->get('_route');
        $hash = $request->get('searchToken', $request->getSession()->get('searchToken'));
        $parcoursTerms = [];
        $parcours = $request->get('parcours', null);

        if ($hash !==null && strpos($route, 'search') !== false) {

            $parcoursTerms = [];
            if ($parcours!==null && $parcours !== 'general') {
                $parcoursTerms = ['parcours' => $parcours];
                $this->bctService->add(
                    'home_thematic',
                    sprintf('breadcrumb.parcours.%s', $parcours),
                    $parcoursTerms
                );
            }

            $parcoursTerms = array_merge([ObjSearch::PARAM_REQUEST_NAME => $hash], $parcoursTerms);

            if ($humanCriteria = $this->humanizeCriteria($request)) {
                $this->bctService->add(
                    null,
                    'breadcrumb.search-terms',
                    [],
                    ['%terms%' => $humanCriteria]
                );
            } else {
                $this->bctService->add(
                    null,
                    'breadcrumb.search',
                    [],
                    $parcoursTerms
                );
            }
            return true;
        }elseif ($hash !==null) {
            if ($parcours && $parcours !== 'general') {

                $parcoursTerms = ['parcours' => $parcours];
                $this->bctService->add(
                    'home_thematic',
                    sprintf('breadcrumb.parcours.%s', $parcours),
                    $parcoursTerms
                );
            }

            if ($humanCriteria = $this->humanizeCriteria($request)) {
                $this->bctService->add(
                    'refined_search',
                    'breadcrumb.search-terms',
                    array_merge([ObjSearch::PARAM_REQUEST_NAME => $hash], $parcoursTerms),
                    ['%terms%' => $humanCriteria]
                );

            } else {
             /*
                $this->bctService->add(
                    'refined_search',
                    'breadcrumb.search',
                    array_merge([ObjSearch::PARAM_REQUEST_NAME => $hash], $parcoursTerms)
                );*/
            }

            return true;
        }
        return false;
    }

    /**
     * @param Request $request
     * @return string
     */
    private function humanizeCriteria(Request $request): string
    {
        $objSearch = new ObjSearch($this->getObjSearchQuery($request));

        return implode(', ', $objSearch->getCriteria()->getKeywordsTitles());
    }

    /**
     * @param Request $request
     * @return SearchQuery|null
     */
    private function getObjSearchQuery(Request $request): ?SearchQuery
    {
        $token = $request->get(ObjSearch::PARAM_REQUEST_NAME);
        $route = $request->get('_route');
        $searchQuery = null;
        $criteria = new Criteria();
        if ($token && ($object = $request->getSession()->get($token))) {
            $searchQuery = $this->searchService->getSearchQueryFromToken($token, $request);
        } elseif ($route === 'saved_search') {
            $searchHistory = $this->entityManager->getRepository(SearchHistory::class)->find($request->get('id'));
            if ($searchHistory instanceof SearchHistory) {
                $searchQuery = $this->searchService->deserializeSearchQuery($searchHistory->getQueryString());
            }
        } elseif ($route === 'advanced_search' || $route === 'advanced_search_parcours') {
            $criteria->setAdvancedSearch($request->query->all());

            $searchQuery = new SearchQuery(
                $criteria,
                new FilterFilter($request->query->all()),
                null,
                SearchQuery::ADVANCED_MODE
            );
        } else {
            $keyword = $request->get(Criteria::SIMPLE_SEARCH_KEYWORD, '');
            $criteria->setSimpleSearch($request->get(Criteria::SIMPLE_SEARCH_TYPE), $keyword);

            $searchQuery = new SearchQuery($criteria);
        }

        if ($route === 'refined_search') {
            $searchQuery->setFacets(new FacetFilter($request->query->all()));
        }

        return $searchQuery;
    }
}

