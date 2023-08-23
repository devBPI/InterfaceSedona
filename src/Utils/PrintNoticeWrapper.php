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
        $this->noticeAuthority[] = $notice;
        return $this;
    }

    public function getNoticeOnShelves(): array
    {
        return $this->noticeOnShelves;
    }

    public function addNoticeOnShelves(Notice $notice = null) :self
    {
        if ($notice instanceof Notice) {
            $this->noticeOnShelves[] = $notice;
        }
        return $this;
    }

    public function getNoticeIndice(): array
    {
        return $this->noticeIndice;
    }

    public function addNoticeIndice(IndiceCdu $notice) :self
    {
        $this->noticeIndice[] = $notice;
        return $this;
    }

}
