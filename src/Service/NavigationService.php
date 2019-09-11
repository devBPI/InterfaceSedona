<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 11/09/19
 * Time: 10:36
 */
namespace App\Service;

use App\Model\Authority;
use App\Model\Facet;
use App\Model\Facets;
use App\Model\Notice;
use App\Model\Results;
use App\Model\Search;
use App\Model\Search\Criteria;
use App\Service\Provider\NoticeAuthorityProvider;
use App\Service\Provider\SearchProvider;

class NavigationService
{

    /**
     * @var string
     */
    private $hash;
    /**
     * @var string|null
     */
    private $previousPermalink;
    /**
     * @var string
     */
    private $actualPermalink;
    /**
     * @var string|null
     */
    private $nextPermalink;
    /**
     * @var int|null
     */
    private $row;
    /**
     * @var int|null
     */
    private $rows;

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param SearchProvider $searchProvider
     * @param Search $search
     * @param $type
     * @return \App\Model\RankedAuthority[]|\App\Model\Subject[]|array
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function getNoticeList(SearchProvider $searchProvider, Search $search, $type){
        $searchResultNotices = $searchProvider->getListBySearch($search->getCriteria(), $search->getFacets());
        if ($type == Authority::class){
            return $searchResultNotices->getAuthoritiesList();
        }

        return $searchResultNotices->getNotices()->getNoticesList();
    }

    /**
     * NavigationService constructor.
     * @param SearchProvider $searchProvider
     * @param string $permalink
     * @param Search|null $search
     * @param string|null $hash
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __construct(SearchProvider $searchProvider, string $permalink, Search $search=null, string $hash=null, $type=Notice::class)
    {
        $this->actualPermalink = $permalink;
        if ($search === null){
            return;
        }
        $this->hash = $hash;

        $notices = $this->getNoticeList($searchProvider, $search, $type);
dump( $notices ); die;
        $this->rows = \count($notices);
        $noticesFiltered = array_filter($notices, function($value) use($permalink){
            if ($value->getPermalink() === $permalink){
                return true;
            }
        });

        if (\count($noticesFiltered)>0){

            $this->row = array_key_first($noticesFiltered);

            if ($this->row > 0){
                $this->previousPermalink = $notices[$this->row-1]->getPermalink();
            }
            if ($this->row < count($notices) - 1) {
                $this->nextPermalink = $notices[$this->row + 1]->getPermalink();
            }
        }
    }

    /**
     * @return int|null
     */
    public function getRow(): ?int
    {
        return $this->row+1;
    }

    /**
     * @return int|null
     */
    public function getRows(): ?int
    {
        return $this->rows;
    }

    /**
     * @return null|string
     */
    public function getPreviousPermalink(): ?string
    {
        return $this->previousPermalink;
    }

    /**
     * @return string
     */
    public function getActualPermalink(): string
    {
        return $this->actualPermalink;
    }

    /**
     * @return null|string
     */
    public function getNextPermalink(): ?string
    {
        return $this->nextPermalink;
    }

}

