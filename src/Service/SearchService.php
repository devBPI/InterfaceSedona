<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Search\FiltersQuery;
use App\Model\Search\ObjSearch;
use App\Model\Search\SearchQuery;
use App\WordsList;
use JMS\Serializer\SerializerInterface;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SearchService
 * @package App\Service
 */
final class SearchService
{
    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var HistoryService
     */
    private $historicService;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * SearchService constructor.
     * @param TranslatorInterface $translator
     * @param SerializerInterface $serializer
     * @param HistoryService $historicService
     * @param Logger $logger
     */
    public function __construct(
        TranslatorInterface $translator,
        SerializerInterface $serializer,
        HistoryService $historicService,
        Logger $logger
    ) {
        $this->translator = $translator;
        $this->serializer = $serializer;
        $this->historicService = $historicService;
        $this->logger = $logger;
    }

    /**
     * @param SearchQuery $search
     * @param Request $request
     * @return ObjSearch
     */
    public function createObjSearch(SearchQuery $search, Request $request): ObjSearch
    {
        $search->setSort($request->get(FiltersQuery::SORT_LABEL, SearchQuery::SORT_DEFAULT));
        $search->setRows($request->get(FiltersQuery::ROWS_LABEL, SearchQuery::ROWS_DEFAULT));
        $search->setPage($request->get(FiltersQuery::PAGE_LABEL, 1));

        $objSearch = new ObjSearch($search);
        $objSearch->setTitle($this->getTitleFromSearchQuery($search));

        try {
            $this->historicService->saveCurrentSearchInSession(
                $objSearch,
                $this->serializer->serialize($search, 'json'),
                $request->get('action', null) !== null
            );
        } catch (\Exception $e) {
            $this->logger->error('Search history failed : '.$e->getMessage());
        }

        return $objSearch;
    }

    /**
     * @param SearchQuery $searchQuery
     * @return string
     */
    private function getTitleFromSearchQuery(SearchQuery $searchQuery): string
    {
        $title = [];
        if ($searchQuery->getCriteria()->getParcours() !== WordsList::THEME_DEFAULT) {
            $title[] = $this->translator->trans('header.result.title.'.$searchQuery->getCriteria()->getParcours());
        } else {
            $title[] = $this->translator->trans('page.search.title.'.strtolower($searchQuery->getMode()));
        }

        $keywords = $searchQuery->getCriteria()->getKeywordsTitles();
        if (count($keywords) > 0) {
            $title[] = implode(', ', $keywords);
        }

        return implode(' - ', $title);
    }

    /**
     * @param string $token
     * @param Request $request
     * @return SearchQuery
     */
    public function getSearchQueryFromToken(string $token, Request $request): SearchQuery
    {
        /** @var SearchQuery $search */
        return $this->deserializeSearchQuery($request->getSession()->get($token));
    }

    /**
     * @param string $object
     * @return SearchQuery
     */
    public function deserializeSearchQuery(string $object): SearchQuery
    {
        return $this->serializer->deserialize(
            $object,
            SearchQuery::class,
            'json'
        );
    }

}
