<?php


namespace App\Controller\Traits;


use App\Entity\SearchHistory;
use App\Model\Search\Criteria;
use App\Model\Search\FacetFilter;
use App\Model\Search\FilterFilter;
use App\Model\Search\ObjSearch;
use App\Model\Search\SearchQuery;
use Symfony\Component\HttpFoundation\Request;

Trait ObjSearchInstanceTrait
{
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
