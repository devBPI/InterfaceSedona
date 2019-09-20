<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Search\Criteria;
use App\Model\Search\SearchQuery;
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
     * SearchService constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getAdvancedTitleFromRequest(Request $request): string
    {
        $keywords = [];
        foreach ($request->get(Criteria::ADVANCED_SEARCH_LABEL) as $queries) {
            $keywords[] = $queries[Criteria::TEXT_LABEL];
        }

        return $this->translator->trans('page.search.title.advanced', ['%keyword%' => implode(', ', $keywords)]);
    }

    /**
     * @param string $keyword
     * @return string
     */
    public function getSimpleTitle(string $keyword): string
    {
        return $this->translator->trans('page.search.title.simple', ['%keyword%' => $keyword]);
    }

    /**
     * @param SearchQuery $searchQuery
     * @return string
     */
    public function getTitleFromSearchQuery(SearchQuery $searchQuery): string
    {
        $keywords = $searchQuery->getCriteria()->getKeywords();
        if (count($keywords) > 1) {
            return $this->translator->trans('page.search.title.advanced', ['%keyword%' => implode(', ', $keywords)]);
        }

        return $this->getSimpleTitle($keywords[0] ?? '');
    }

}
