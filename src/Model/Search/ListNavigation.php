<?php

namespace App\Model\Search;


use App\Model\Results;

/**
 * Class ListNavigation
 * @package App\Model\Search
 */
final class ListNavigation
{
    /**
     * @var Navigation
     */
    private $listNotices;
    /**
     * @var Navigation
     */
    private $listOnlineNotices;
    /**
     * @var Navigation
     */
    private $authorities;

    /**
     * ListNavigation constructor.
     * @param ObjSearch $objSearch
     */
    public function __construct(ObjSearch $objSearch)
    {
        $hash = $objSearch->getContextToken();

        $result = $objSearch->getResults();
        $this->listNotices = new Navigation($hash, $result->getNotices());
        $this->listOnlineNotices = new Navigation($hash, $result->getNoticesOnline());
        $this->authorities = new Navigation($hash, new AuthorityList($result->getAuthors()));
    }

    /**
     * @param Results $results
     */
    public function addNotices(Results $results): void
    {
        $this->listNotices->addPermalinks($results->getNotices());
        $this->listOnlineNotices->addPermalinks($results->getNoticesOnline());
    }

    /**
     * @return Navigation
     */
    public function getListNotices(): ?Navigation
    {
        return $this->listNotices;
    }

    /**
     * @return Navigation
     */
    public function getListOnlineNotices(): ?Navigation
    {
        return $this->listOnlineNotices;
    }

    /**
     * @return Navigation
     */
    public function getAuthorities(): ?Navigation
    {
        return $this->authorities;
    }
}
