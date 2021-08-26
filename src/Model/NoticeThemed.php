<?php
declare(strict_types=1);

namespace App\Model;

use App\Request\ParamConverter\BpiConverterInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Class NoticeThemed
 * @package App\Model
 */
class NoticeThemed implements BpiConverterInterface
{
    /**
     * @var Notices
     * @JMS\Type("App\Model\Notices")
     * @JMS\SerializedName("notices-same-theme")
     */
    private $noticesSameTheme;
    /**
     * @var Notice
     * @JMS\Type("App\Model\Notice")
     * @JMS\SerializedName("notice-online")
     */
    private $onlineNotices;
    /**
     * @var NoticesOnline
     * @JMS\Type("App\Model\NoticesOnline")
     * @JMS\SerializedName("notices-same-theme")
     */
    private $onlineNoticesSameTheme;

    /**
     * @var Notice
     *
     * @JMS\Type("App\Model\Notice")
     */
    private $notice;

    /**
     * @var array
     * @JMS\Type("array<int>")
     * @JMS\SerializedName("notices-same-theme")
     * @JMS\XmlList(entry="results")
     */
    private $results;

    /**
     * @return AbstractListNotices|null
     */
    public function getNoticesSameTheme(): ?AbstractListNotices
    {
        if ($this->onlineNoticesSameTheme instanceof NoticesOnline &&
            count($this->onlineNoticesSameTheme->getNoticesList()) > 0) {
            return $this->onlineNoticesSameTheme;
        }

        return $this->noticesSameTheme;
    }

    /**
     * @return Notice|null
     */
    public function getNotice(): ?Notice
    {
        return $this->notice instanceof Notice?$this->notice:$this->onlineNotices;
    }

    /**
     * @return int
     */
    public function getResults():int
    {
        return count($this->results)>0?$this->results[0]:0;
    }

    /**
     * @return null|string
     */
    public function getPermalink(): ?string
    {
        if ($this->getNotice() instanceof Notice) {
            return $this->getNotice()->getPermalink();
        }

        return null;
    }
}
