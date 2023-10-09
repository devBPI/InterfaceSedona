<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 19/09/19
 * Time: 18:31
 */

namespace App\Utils;



use App\Model\Authority;
use App\Model\IndiceCdu;
use App\Model\Notice;
use App\Model\NoticeThemed;

class PrintNoticeWrapper
{
    CONST MAX_NOTICE = 100;
    private int $nbNotice = 0;

    private array $noticeOnline=[];

    private array $noticeAuthority=[];

    private array $noticeOnShelves=[];

    private array $noticeIndice=[];

    public function getNoticeOnline(): array
    {
        return $this->noticeOnline;
    }

    public function getNoticeAuthority(): array
    {
        return $this->noticeAuthority;
    }

    public function addNoticeAuthority(Authority $notice) :self
    {
        if ($this->nbNotice >= self::MAX_NOTICE) {
            return $this;
        }
        $this->noticeAuthority[] = $notice;
        $this->nbNotice++;
        return $this;
    }

    public function getNoticeOnShelves(): array
    {
        return $this->noticeOnShelves;
    }

    public function addNoticeOnShelves(Notice $notice = null) :self
    {
        if (!$notice instanceof Notice || ($this->nbNotice >= self::MAX_NOTICE)) {
            return $this;
        }
        $this->noticeOnShelves[] = $notice;
        $this->nbNotice++;
        return $this;
    }

    public function getNoticeIndice(): array
    {
        return $this->noticeIndice;
    }

    public function addNoticeIndice(IndiceCdu $notice) :self
    {
        if ($this->nbNotice >= self::MAX_NOTICE) {
            return $this;
        }
        $this->noticeIndice[] = $notice;
        $this->nbNotice++;
        return $this;
    }

}
