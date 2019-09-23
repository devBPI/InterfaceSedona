<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 29/08/19
 * Time: 16:29
 */
declare(strict_types=1);

namespace App\Model;
use JMS\Serializer\Annotation as JMS;


class NoticeThemed
{
    /**
     * @var Notices
     * @JMS\Type("App\Model\Notices")
     * @JMS\SerializedName("notices-same-theme")
     * @JMS\XmlList(entry="noticesList")
     */
    private $noticesSameTheme;

    /**
     * @var Notice
     *
     * @JMS\Type("App\Model\Notice")
     *
     */
    private $notice;

    /**
     * @return Notices
     */
    public function getNoticesSameTheme()
    {
        return $this->noticesSameTheme;
    }

    /**
     * @return Notice
     */
    public function getNotice(): Notice
    {
        return $this->notice;
    }

}
