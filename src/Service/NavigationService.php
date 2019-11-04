<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Authority;
use App\Model\IndiceCdu;
use App\Model\Interfaces\NoticeInterface;
use App\Model\Notice;
use App\Model\RankedAuthority;
use App\Model\Search\SearchQuery;
use App\Service\Provider\SearchProvider;
use App\Utils\NavigationNotice;

/**
 * Class NavigationService
 * @package App\Service
 */
class NavigationService
{
    /**
     * @var string
     */
    private $hash;
    /**
     * @var NavigationNotice|null
     */
    private $previousPermalink;
    /**
     * @var NavigationNotice
     */
    private $actualPermalink;
    /**
     * @var NavigationNotice|null
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
     * @var SearchProvider
     */
    private $searchProvider;
    /**
     * @var SearchQuery|null
     */
    private $search;

    /**
     * NavigationService constructor.
     * @param SearchProvider $searchProvider
     */
    public function __construct(
        SearchProvider $searchProvider
    ) {
        $this->searchProvider = $searchProvider;
    }

    /**
     * @param string $permalink
     * @param SearchQuery $search
     * @param string|null $hash
     * @param null $type
     * @return NavigationService
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function build( string $permalink,  SearchQuery $search, string $hash = null, $type = null):NavigationService
    {
        $this->hash = $hash;
        $this->search = $search;
        $notices = $this->getNoticeList($this->searchProvider, $this->search, $type);
        $this->rows = \count($notices);

        $noticesFiltered = array_filter(
            $notices,
            function (NoticeInterface $value) use ($permalink) {
                if ($value->getPermalink() === $permalink) {
                    return true;
                }
            }
        );

        if (\count($noticesFiltered) > 0) {
            $this->row = \array_key_first($noticesFiltered);
            $this->actualPermalink = new NavigationNotice($permalink, get_class($notices[$this->row]));
            if ($this->row > 0) {
                $this->previousPermalink = new NavigationNotice(
                    $notices[$this->row - 1]->getPermalink(),
                    $notices[$this->row - 1]->getClassName()
                );
            }

            if ($this->row < count($notices) - 1) {
                $this->nextPermalink = new NavigationNotice(
                    $notices[$this->row + 1]->getPermalink(),
                    $notices[$this->row + 1]->getClassName()
                );
            }
        }

        return $this;
    }

    /**
     * @param SearchProvider $searchProvider
     * @param SearchQuery $search
     * @param $type
     * @return \App\Model\RankedAuthority[]|\App\Model\Subject[]|array
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function getNoticeList(SearchProvider $searchProvider, SearchQuery $search, $type)
    {
        $searchResultNotices = $searchProvider->getListBySearch($search);

        if ($type === RankedAuthority::class) {
            return $searchResultNotices->getAuthoritiesList();
        }

        if ($type === Notice::ON_LIGNE) {
            return $searchResultNotices->getNoticesOnline()->getNoticesList();
        }

        return $searchResultNotices->getNotices()->getNoticesList();
    }

    /**
     * @param string|null $value
     * @return string
     */
    public static function getRouteByObject(string $value = null)
    {
        switch ($value) {
            case Authority::class:
                return 'record_authority';
            case IndiceCdu::class:
                return 'record_indice_cdu';
            case Notice::class:
                return 'record_bibliographic';
            default:
                throw new  \InvalidArgumentException(sprintf('route not finded for  %s', $value));
        }
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return int|null
     */
    public function getRow(): ?int
    {
        return $this->row++;
    }

    /**
     * @return int|null
     */
    public function getRows(): ?int
    {
        return $this->rows;
    }

    /**
     * @return NavigationNotice|null
     */
    public function getPreviousPermalink(): ?NavigationNotice
    {
        return $this->previousPermalink;
    }

    /**
     * @return NavigationNotice
     */
    public function getActualPermalink(): NavigationNotice
    {
        return $this->actualPermalink;
    }

    /**
     * @return NavigationNotice|null
     */
    public function getNextPermalink(): ?NavigationNotice
    {
        return $this->nextPermalink;
    }

    /**
     * @return SearchQuery|null
     */
    public function getSearch(): SearchQuery
    {
        return $this->search;
    }
}
