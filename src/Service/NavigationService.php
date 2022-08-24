<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Authority;
use App\Model\Exception\NoResultException;
use App\Model\Facets;
use App\Model\IndiceCdu;
use App\Model\Interfaces\NoticeInterface;
use App\Model\Notice;
use App\Model\Search\ListNavigation;
use App\Model\Search\Navigation;
use App\Model\Search\ObjSearch;
use App\Model\Search\SearchQuery;
use App\Service\Provider\SearchProvider;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class NavigationService
 * @package App\Service
 */
final class NavigationService
{
    const SESSION_KEY = 'navigation';

    /**
     * @var SearchProvider
     */
    private $searchProvider;

    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var SearchService
     */
    private $searchService;

    /**
     * @var int
     */
    public $page;

    /**
     * NavigationService constructor.
     * @param SessionInterface $session
     * @param RequestStack $requestStack
     * @param SearchProvider $searchProvider
     * @param SearchService $searchService
     */
    public function __construct(
        SessionInterface $session,
        RequestStack $requestStack,
        SearchProvider $searchProvider,
        SearchService $searchService
    ) {
        $this->session = $session;
        $this->searchProvider = $searchProvider;
        $this->requestStack = $requestStack;
        $this->searchService = $searchService;
    }

    /**
     * @param string|null $value
     * @return string
     */
    public static function getRouteByObject(string $value = null)
    {
        switch ($value) {
            case Authority::class:
                return 'record_authority';
            case IndiceCdu::class:
                return 'record_indice_cdu';
            case Notice::class:
                return 'record_bibliographic';
            default:
                throw new \InvalidArgumentException(sprintf('route not finded for  %s', $value));
        }
    }

    /**
     * @param Notice $notice
     * @return Navigation
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function buildNotices(Notice $notice): ?Navigation
    {
        $listNavigation = $this->getContainer();

        $navigation = $notice->isOnLine() ? $listNavigation->getListOnlineNotices() : $listNavigation->getListNotices();
        $navigation->setCurrentIndex($notice->getPermalink());
        try {
            $navigation->setPreviousLink();
        } catch (NoResultException $exception) {
            if ($this->addMoreNotices($listNavigation, -1)) {
                $navigation->setPreviousLink();
            }
        }

        try {
            $navigation->setNextLink();
        } catch (NoResultException $exception) {
            if ($this->addMoreNotices($listNavigation, 1)) {
                $navigation->setNextLink();
            }
        }

        return $navigation;
    }

    /**
     * @param NoticeInterface $notice
     * @return Navigation|null
     */
    public function buildAuthorities(NoticeInterface $notice): ?Navigation
    {
        $listNavigation = $this->getContainer();
        $navigation = $listNavigation->getAuthorities();
        $navigation->setCurrentIndex($notice->getPermalink());
        try {
            $navigation->setPreviousLink();
            $navigation->setNextLink();
        } catch (NoResultException $e) {}

        return $navigation;
    }

    /**
     * @return ListNavigation
     */
    public function getContainer(): ListNavigation
    {
        if (!$this->session->has(self::SESSION_KEY)) {
            throw new NoResultException('Session has expired');
        }

        /** @var ListNavigation $listNavigation */
        $listNavigation = \unserialize($this->session->get(self::SESSION_KEY));

        return $listNavigation;
    }

    /**
     * @param ListNavigation $listNavigation
     * @param int $page
     * @return bool
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function addMoreNotices(ListNavigation $listNavigation, int $page): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        $searchToken = $request->get(ObjSearch::PARAM_REQUEST_NAME);
        if ($searchToken) {
            $searchQuery = $this->searchService->getSearchQueryFromToken($searchToken, $request);
            $searchQuery->setPage($searchQuery->getPage() + $page);
            $links = $this->searchProvider->getListBySearch($searchQuery);

            $listNavigation->addNotices($links);
            $this->session->set(NavigationService::SESSION_KEY, serialize($listNavigation));

            return true;
        }

        return false;
    }

    /**
     * @return int|null
     */
    public function getSearchRows():?int{

        $searchToken = $this->getSearchToken();
        if (!$searchToken ||  !$this->session->has($searchToken)) {
            return SearchQuery::ROWS_DEFAULT;
        }
        $query = json_decode($this->session->get($searchToken), true);

       return array_key_exists('rows', $query)?$query['rows']:null;
    }

    /**
     * @return string|null
     */
    public function getSeeAll():?string
    {
        $searchToken = $this->getSearchToken();
        if (!$searchToken ||  !$this->session->has($searchToken)) {
            return null;
        }

        $query = json_decode($this->session->get($searchToken), true);

        return array_key_exists('see-all', $query)?$query['see-all']:null;
    }

    private function getSearchToken(){
        $request = $this->requestStack->getCurrentRequest();
       return $request->get(ObjSearch::PARAM_REQUEST_NAME, null);
    }

}
