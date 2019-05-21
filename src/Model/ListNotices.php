<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class ListNotices
 * @package App\Model
 *
 * @JMS\XmlRoot("notices")
 */
class ListNotices extends AbstractListNotices
{
    /**
     * @var array|Notice[]
     * @JMS\Type("array<App\Model\Notice>")
     * @JMS\SerializedName("noticesList")
     * @JMS\XmlList(entry="notice")
     */
    private $noticesList = [];

    /**
     * @return Notice[]|array
     */
    public function getNoticesList(): array
    {
        return $this->noticesList;
    }
}
