<?php

namespace App\Model;

use App\Model\Interfaces\NavigationInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Class AbstractListNotices
 * @package App\Model
 */
abstract class AbstractListNotices implements NavigationInterface
{
    /**
     * @var array|Facet[]
     * @JMS\Type("array<App\Model\Facet>")
     * @JMS\XmlList(entry="facet")
     */
    private $facetsList = [];

    /**
     * @var integer
     * @JMS\Type("integer")
     * @JMS\SerializedName("results")
     */
    private $totalCount;

    /**
     * @var integer
     * @JMS\Type("integer")
     */
    private $numPages;

    /**
     * @var integer
     * @JMS\Type("integer")
     */
    private $page;

    /**
     * @var integer
     * @JMS\Type("integer")
     */
    private $rows;


    /**
     * @return array|Notice[]
     */
    abstract function getNoticesList(): array;

    /**
     * @return Facet[]|array
     */
    public function getFacetsList(): array
    {
        return $this->facetsList;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return int
     */
    public function getNumPages(): int
    {
        return $this->numPages;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

}
