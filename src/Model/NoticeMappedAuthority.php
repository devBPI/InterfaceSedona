<?php
declare(strict_types=1);

namespace App\Model;
use JMS\Serializer\Annotation as JMS;

/**
 * Class NoticeMappedAuthority
 * @package App\Model
 */
class NoticeMappedAuthority
{
    /**
     * @var array|Notice[]
     * @JMS\Type("array<App\Model\Notice>")
     * @JMS\SerializedName("mappedNotices")
     * @JMS\XmlList(entry="mappedNotice")
     */
    private $mappedNotices;

    /**
     * @var Pagination
     *
     * @JMS\Type("App\Model\Pagination")
     */
    private $pagination;

    /**
     * @var integer
     * @JMS\Type("int")
     */
    private $totalResultsNumber;


    /**
     * @return Notice[]|array
     */
    public function getMappedNotices()
    {
        return $this->mappedNotices;
    }

    /**
     * @return Pagination
     */
    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    /**
     * @return int
     * @JMS\XmlList(entry="totalResultsNumber")
     */
    public function getTotalResultsNumber(): int
    {
        return $this->totalResultsNumber;
    }

}

