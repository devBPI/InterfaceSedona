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
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\NoticeProvider;

class PrintNoticeWrapper
{
    /**
     * @var array
     */
    private $noticeOnline=[];
    /**
     * @var array
     */
    private $noticeAuthority=[];
    /**
     * @var array
     */
    private $noticeOnShelves=[];
    /**
     * @var array
     */
    private $noticeIndice=[];

    /**
     * PrintNoticeWrapper constructor.
     * @param array $noticeOnline
     * @param array $noticeAuthority
     * @param array $noticeOnShelves
     * @param array $noticeIndice
     */
    public function __construct(array $noticeOnline, array $noticeAuthority, array $noticeOnShelves=[], array $noticeIndice=[])
    {
        $this->noticeOnline = $noticeOnline;
        $this->noticeAuthority = $noticeAuthority;
        $this->noticeOnShelves = $noticeOnShelves;
        $this->noticeIndice = $noticeIndice;
    }

    /**
     * @return array
     */
    public function getNoticeOnline(): array
    {
        return $this->noticeOnline;
    }

    /**
     * @return array
     */
    public function getNoticeAuthority(): array
    {
        return $this->noticeAuthority;
    }

    /**
     * @return array
     */
    public function getNoticeOnShelves(): array
    {
        return $this->noticeOnShelves;
    }

    /**
     * @return array
     */
    public function getNoticeIndice(): array
    {
        return $this->noticeIndice;
    }




}
