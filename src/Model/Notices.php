<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Notices
 * @package App\Model
 */
class Notices extends AbstractListNotices
{
    /**
     * @var array|Notice[]
     * @JMS\Type("array<App\Model\Notice>")
     * @JMS\SerializedName("noticesList")
     * @JMS\XmlList(entry="notice")
     */
    private $noticesList = [];

    /**
     * @var array|Subject[]
     * @JMS\Type("array<App\Model\Subject>")
     * @JMS\SerializedName("MappedNotices")
     */
    private $mappedNotices = [];

    /**
     * @return Subject[]|array
     */
    public function getNoticesList(): array
    {
        return $this->noticesList;
    }

    /**
     * @return Notice[]|array
     */
    public function getMappedNotices()
    {
        return $this->mappedNotices;
    }
}

