<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: infra
 * Date: 30/10/19
 * Time: 18:18
 */

namespace App\Service;

use App\Model\Interfaces\RecordInterface;
use App\Model\NoticeThemed;
use App\Model\Search\Criteria;
use App\Model\Search\FacetFilter;
use App\Model\Search\ObjSearch;
use App\Model\Search\SearchQuery;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

final class BreadCrumbBuilder
{
    use SearchQueryTrait;
    /**
     * @param string $token
     * @param Request $request
     * @return SearchQuery
     */

    const RECORD = 'record';
    const SEARCH = 'search';
    const HELP = 'help';
    const USER = 'user';
    const HOME = 'home';
    /**
     * @var BreadCrumbTrailService
     */
    private $bctService;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * BreadCrumbBuilder constructor.
     * @param BreadCrumbTrailService $bctService
     * @param SerializerInterface $serializer
     */
    public function __construct(BreadCrumbTrailService $bctService, SerializerInterface $serializer)
    {
        $this->bctService = $bctService;
        $this->serializer = $serializer;
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
    public function build(Request $request):bool
    {
        $route = $request->get('_route');

        if (strpos($route, self::USER) !== false){
            return $this->buildForUserPage($request);
        }
        if (strpos($route, self::HELP) !== false){
            return $this->buildForHelpPage($request);
        }
        if (strpos($route, self::HOME) !== false && $route!=='home2'){
            return $this->buildForHome($request);
        }
        if (strpos($route, self::RECORD) !== false){
            return $this->buildForCardPage($request);
        }
        if (strpos($route, self::SEARCH) !== false){
            return $this->buildForSearchPage($request);
        }

        return false;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function buildForHome(Request $request):bool
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
    private function buildForCardPage(Request $request):bool
    {
        $notice = $request->get('notice');
        $this->buildForSearchPage($request);

        if ($notice instanceof NoticeThemed){
            $notice = $notice->getNotice();
        }

        if ($notice instanceof RecordInterface) {
            $this->bctService->add(
                sprintf('record_%s', $notice->getBreadcrumbName()),
                sprintf('breadcrumb.card.%s', $notice->getBreadcrumbName()),
                ['permalink' => $notice->getPermalink()],
                ['%title%'   => $notice->getTitle()]
            );
        }

        return true;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function buildForSearchPage(Request $request):bool
    {
        $route = $request->get('_route');
        $navigation = $request->get('navigation');
        $parcoursTerms =[];
        if ($navigation instanceof NavigationService){
            if (($parcours = $navigation->getSearch()->getCriteria()->getParcours()) && $parcours!=='general'){
                $parcoursTerms = ['parcours'=>$parcours];
                $this->bctService->add(
                    'home_thematic',
                    sprintf('breadcrumb.parcours.%s', $parcours),
                    $parcoursTerms
                );
            }

            if ($humanCriteria = $this->humanizeCriteria($request)){
                $this->bctService->add(
                    'refined_search',
                    'breadcrumb.search-terms',
                    array_merge(['token'  => $navigation->getHash()], $parcoursTerms),
                    ['%terms%'      => $humanCriteria]
                );
            }else {
                $this->bctService->add(
                    'refined_search',
                    'breadcrumb.search',
                    array_merge(['token'  => $navigation->getHash()], $parcoursTerms)
                );
            }

        }else if (strpos($route, 'search')!==false){
            if (($parcours = $request->get('parcours')) && $parcours !== 'general'){
                $parcoursTerms = ['parcours'=>$parcours];
                $this->bctService->add(
                    'home_thematic',
                    sprintf('breadcrumb.parcours.%s', $parcours),
                   $parcoursTerms
                );
            }

            $keyword = $request->get(Criteria::SIMPLE_SEARCH_KEYWORD, '');
            $criteria = new Criteria();
            $criteria->setSimpleSearch($request->get(Criteria::SIMPLE_SEARCH_TYPE), $keyword);
            $searchToken = $request->getSession()->get('searchToken');

            if ($humanCriteria = $this->humanizeCriteria($request)){
                $this->bctService
                    ->add(
                        'refined_search',
                        'breadcrumb.search-terms',
                        array_merge(['token'=>$searchToken], $parcoursTerms),
                        ['%terms%'=> $humanCriteria]);
            }else {
                $this->bctService->add(
                    'refined_search',
                    'breadcrumb.search',
                    array_merge(['token'=>$searchToken], $parcoursTerms)
                );
            }
        }

        return true;
    }
    /**
     * @param Request $request
     * @return bool
     */
    private function buildForHelpPage(Request $request):bool
    {
        $route = $request->get('_route');
        $this->bctService->add('help_search', 'breadcrumb.help_index');
        $this->bctService->add($route,sprintf('breadcrumb.help.%s',$route));

        return true;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function buildForUserPage(Request $request)
    {
        $route = $request->get('_route');
        $this->bctService->add($route, sprintf('breadcrumb.user.%s',$route));

        return true;
    }

    /**
     * @param Request $request
     * @return SearchQuery|null
     */
    private function getObjSearchQuery(Request $request):?SearchQuery
    {
        $token      = $request->get('token',  $request->query->get('searchToken'));
        $route      = $request->get('_route');

        $searchQuery = null;
        $criteria = new Criteria();
        if ($token ){
            $searchQuery =  $this->getSearchQueryFromToken($token,$request);
        }
        else if ($route === 'advanced_search'){
            $criteria->setAdvancedSearch($request->query->all());

            $searchQuery =  new SearchQuery($criteria, new FacetFilter($request->query->all()), SearchQuery::ADVANCED_MODE);
        }
        else{
            $keyword = $request->get(Criteria::SIMPLE_SEARCH_KEYWORD, '');
            $criteria->setSimpleSearch($request->get(Criteria::SIMPLE_SEARCH_TYPE), $keyword);

            $searchQuery = new SearchQuery($criteria);
        }

        if ($route === 'refined_search'){
            $searchQuery->setFacets(new FacetFilter($request->query->all()));
        }

        return $searchQuery;
    }

    /**
     * @param Request $request
     * @return null|string
     */
    private function humanizeCriteria(Request $request):?string
    {
        $objSearch =  new ObjSearch($this->getObjSearchQuery($request));
        $keywords = $objSearch->getCriteria()->getKeywordsTitles();

        if ($keywords ){
           return implode(',', $keywords);
        }

        return null;
    }
}

