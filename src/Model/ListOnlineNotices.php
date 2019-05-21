<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class ListOnlineNotices
 * @package App\Model
 *
 * @JMS\XmlRoot("notices-online")
 */
class ListOnlineNotices extends AbstractListNotices
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
