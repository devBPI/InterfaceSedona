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
    public const PAGE_LABEL = 'page';
    public const SORT_LABEL = 'sort';
    public const ROWS_LABEL = 'rows';

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
        $this->baseUri = $request->getPathInfo();

        if ($request->query->has(self::PAGE_LABEL)) {
            $currentPage = (int)$request->get(self::PAGE_LABEL);
            $request->query->remove(self::PAGE_LABEL);
        }
        $this->params = new ArrayCollection($request->query->all());

        if (isset($currentPage)) {
            $this->setPage($currentPage);
        }
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->params->set(self::PAGE_LABEL, $page);
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
        $this->params->set(self::SORT_LABEL, $field);
    }

    /**
     * @param int $count
     */
    public function setRows(int $count): void
    {
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
