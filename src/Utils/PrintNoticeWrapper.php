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

    /**
     * @param $array
     * @param NoticeProvider $noticeProvider
     * @param NoticeAuthorityProvider $provider
     * @return PrintNoticeWraper
     */
    public function __invoke($array, NoticeProvider $noticeProvider, NoticeAuthorityProvider $provider):PrintNoticeWrapper
    {

        if (array_key_exists('onshelves', $array)){
            foreach ($array['onshelves'] as $value){
                if (($notice=$noticeProvider->getNotice($value)->getNotice()) instanceof Notice) {
                    $this->noticeOnShelves[] = $notice;
                }
            }
        }        
        if (array_key_exists('online', $array)){
            foreach ($array['online'] as $value){
                if (($notice=$noticeProvider->getNotice($value)->getNotice()) instanceof Notice){
                    $this->noticeOnline[] = $notice;
                }
            }
        }        
        if (array_key_exists('authority', $array)){
            foreach ($array['authority'] as $value){
                if (($notice=$provider->getAuthority($value)) instanceof Authority) {
                    $this->noticeAuthority[] = $notice;
                }
            }
        }        
        if (array_key_exists('indice', $array)){
            foreach ($array['indice'] as $value){
                if (($notice=$provider->getIndiceCdu($value)) instanceof IndiceCdu){
                    $this->noticeIndice[] =$notice;
                }
            }
        }

        return $this;

    }
}
