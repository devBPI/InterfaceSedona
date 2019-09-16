<?php
declare(strict_types=1);

namespace App\Service\Provider;

use App\Model\Author;
use App\Model\Authority;
use App\Model\Exception\NoResultException;
use App\Model\IndiceCdu;
use App\Model\ListNotices;
use App\Model\ListOnlineNotices;
use App\Model\Notice;
use App\Model\NoticeMappedAuthority;
use App\Model\Notices;
use App\Model\NoticeThemed;

/**
 * Class NoticeProvider
 * @package App\Service\Provider
 */
class NoticeAuthorityProvider extends AbstractProvider
{
    /**
     * @param int $id
     * @return
     */
    public function getSubjectNotice(int $id)
    {
          try {
            $content = $this
                ->hydrateFromResponse('/details/authority/'.$id.'/notices/notices-sujets', [], NoticeMappedAuthority::class)
                ;
        } catch (NoResultException $exception) {
            return '';
        }

        return $content;
    }
    /**
     * @TODO à mettre dans un nouveau provider
     */

    /**
     * @param $id
     * @return object|string
     */
    public function getAuthorsNotice($id)
    {
        try {
            $content = $this
                ->hydrateFromResponse('/details/authority/'.$id.'/notices/notices-auteurs', [], NoticeMappedAuthority::class)
                ;
        } catch (NoResultException $exception) {
            return '';
        }

        return $content;
    }

    /**
     * @param $query
     * @return array|object
     */
    public function getAuthority($query)
    {
        $notices = [];
        try{
            $notices = $this->hydrateFromResponse(sprintf('/details/authority/%s', $query), [], Authority::class);

        }catch(NoResultException $e){
            dump("la ressource n'est plus disponible page 404 customisé à faire");
        }

        return $notices;
    }
    /**
     * @param $query
     * @return array|object
     */
    public function getIndiceCdu($query)
    {
        $notices = [];
        try{
            $notices = $this->hydrateFromResponse(sprintf('/details/indice-cdu/%s', $query), [], IndiceCdu::class);

        }catch(NoResultException $e){
            dump("la ressource n'est plus disponible page 404 customisé à faire");
        }

        return $notices;
    }
    /**
     * @param int $id
     * @return
     */
    public function getSubjectIndice(int $id)
    {
        try {
            $content = $this
                ->hydrateFromResponse('/details/indice-cdu/'.$id.'/notices/notices-sujets', [], NoticeMappedAuthority::class)
            ;
        } catch (NoResultException $exception) {
            return '';
        }

        return $content;
    }
}

