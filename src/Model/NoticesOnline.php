<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class NoticesOnline
 * @package App\Model
 */
class NoticesOnline extends AbstractListNotices
{
    /**
     * @var array|Notice[]
     * @JMS\Type("array<App\Model\Notice>")
     * @JMS\SerializedName("noticesOnlineList")
     * @JMS\XmlList(entry="notice-online")
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
