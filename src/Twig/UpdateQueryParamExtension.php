<?php
declare(strict_types=1);

namespace App\Twig;


use Symfony\Component\HttpFoundation\RequestStack;
use App\Model\Search\FiltersQuery;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class UpdateQueryParamExtension
 * @package App\Twig
 */
class UpdateQueryParamExtension extends AbstractExtension
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * UpdateQueryParamExtension constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('add_page_to_uri', [$this, 'changePageParamToUri']),
            new TwigFunction('add_see_all_into_uri', [$this, 'changeSeeAllParamIntoUri']),
            new TwigFunction('add_filter_to_uri', [$this, 'addFilterToUri']),
            new TwigFunction('add_sorting_to_uri', [$this, 'addSortingToUri']),
            new TwigFunction('add_rows_to_uri', [$this, 'addRowsToUri']),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'update_query_param_extension';
    }

    /**
     * @param int $page
     *
     * @return string
     */
    public function changePageParamToUri($page = 1): string
    {
        $filtersQuery = new FiltersQuery($this->requestStack->getMasterRequest());

        $filtersQuery->setPage($page);

        return $filtersQuery->toUrl();
    }

    /**
     * @param string|null $seeAll
     * @return string
     */
    public function changeSeeAllParamIntoUri(string $seeAll = null): string
    {
        $filtersQuery = new FiltersQuery($this->requestStack->getMasterRequest());
        $filtersQuery->setSeeAll($seeAll);

        return $filtersQuery->toUrl();
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function addSortingToUri(string $field): string
    {
        $filtersQuery = new FiltersQuery($this->requestStack->getMasterRequest());

        $filtersQuery->setSorting($field);

        return $filtersQuery->toUrl();
    }

    /**
     * @param int $count
     * @return string
     */
    public function addRowsToUri(int $count = 10): string
    {
        $filtersQuery = new FiltersQuery($this->requestStack->getMasterRequest());

        $filtersQuery->setRows($count);

        return $filtersQuery->toUrl();
    }

}
