<?php
declare(strict_types=1);

namespace App\Model\Search;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FiltersQuery
 * @package ThermorSiteBundle\Model\Search
 */
class FiltersQuery
{
    public const PAGE_LABEL     = 'page';
    private const SEE_ALL_LABEL  = 'see-all';
    public const SORT_LABEL     = 'sort';
    public const ROWS_LABEL     = 'rows';

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var ArrayCollection
     */
    private $params;

    /**
     * FiltersQuery constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->params = new ArrayCollection($request->query->all());
        $this->baseUri = $request->getPathInfo();
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->params->set(self::PAGE_LABEL, $page);
    }

    /**
     * @param string|null $seeAll
     */
    public function setSeeAll(string $seeAll=null): void
    {
        $this->params->set(self::SEE_ALL_LABEL, $seeAll);
    }

    /**
     * @return ArrayCollection
     */
    public function getParams(): ArrayCollection
    {
        return $this->params;
    }
    /**
     * @param string $field
     */
    public function setSorting(string $field): void
    {
        $this->params->set(self::PAGE_LABEL, 0);
        $this->params->set(self::SORT_LABEL, $field);
    }

    /**
     * @param int $count
     */
    public function setRows(int $count): void
    {
        $this->params->set(self::PAGE_LABEL, 0);
        $this->params->set(self::ROWS_LABEL, $count);
    }

    /**
     * @return string
     */
    public function toUrl(): string
    {
        $url = $this->baseUri;
        if ($this->params->count() === 0) {
            return $url;
        }

        return $url.'?'.http_build_query($this->params->toArray());
    }
}

