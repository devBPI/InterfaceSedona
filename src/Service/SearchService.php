<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Search\FiltersQuery;
use App\Model\Search\ObjSearch;
use App\Model\Search\SearchQuery;
use JMS\Serializer\SerializerInterface;
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
     * @var HistoricService
     */
    private $historicService;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * SearchService constructor.
     * @param TranslatorInterface $translator
     * @param SerializerInterface $serializer
     * @param HistoricService $historicService
     */
    public function __construct(
        TranslatorInterface $translator,
        SerializerInterface $serializer,
        HistoricService $historicService
    ) {
        $this->translator = $translator;
        $this->serializer = $serializer;
        $this->historicService = $historicService;
    }

    /**
     * @param SearchQuery $searchQuery
     * @return string
     */
    private function getTitleFromSearchQuery(SearchQuery $searchQuery): string
    {
        $keywords = $searchQuery->getCriteria()->getKeywordsTitles();
        if (count($keywords) > 1) {
            return $this->translator->trans('page.search.title.advanced', ['%keyword%' => implode(', ', $keywords)]);
        } elseif (!isset($keywords[0])) {
            return '';
        }

        return $this->translator->trans('page.search.title.simple', ['%keyword%' => $keywords[0]]);
    }

    /**
     * @param SearchQuery $search
     * @param Request $request
     * @return ObjSearch
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createObjSearch(SearchQuery $search, Request $request): ObjSearch
    {
        $title = $this->getTitleFromSearchQuery($search);
        if ($request->get('action', null) !== null) {
            $request->query->remove('action');
            $this->historicService->saveMyHistoric($title, $this->serializer->serialize($search, 'json'));
        }

        $search->setSort($request->get(FiltersQuery::SORT_LABEL, SearchQuery::SORT_DEFAULT));
        $search->setRows($request->get(FiltersQuery::ROWS_LABEL, SearchQuery::ROWS_DEFAULT));
        $search->setPage($request->get(FiltersQuery::PAGE_LABEL, 1));


        $hash = \spl_object_hash($search);
        $request->getSession()->set($hash, $this->serializer->serialize($search, 'json'));

        $objSearch = new ObjSearch($search);
        $objSearch
            ->setTitle($title)
            ->setContext($hash, $this->serializer->serialize($search, 'json'));

        return $objSearch;
    }

    /**
     * @param string $token
     * @param Request $request
     * @return SearchQuery
     */
    public function getSearchQueryFromToken(string $token, Request $request): SearchQuery
    {
        /** @var SearchQuery $search */
        return $this
            ->serializer
            ->deserialize(
                $request->getSession()->get($token),
                SearchQuery::class,
                'json'
            );
    }

    /**
     * @param string $object
     * @return SearchQuery
     */
    public function deserializeSearchQuery(string $object): SearchQuery
    {
        return $this
            ->serializer
            ->deserialize(
                $object,
                SearchQuery::class,
                'json'
            );
    }

}
