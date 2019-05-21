<?php

namespace App\Model;


/**
 * Class SearchResult
 * @package App\Model
 */
class SearchResult
{
    /**
     * @var string
     */
    private $query;

    /**
     * @var Facet[]
     */
    private $facets;

    /**
     * @var Author[]
     */
    private $authors = [];

    /**
     * @var Notice[]
     */
    private $notices = [];

    /**
     * @var Notice[]
     */
    private $onlineNotices = [];

    /**
     * SearchResult constructor.
     * @param string $search
     */
    public function __construct(string $search)
    {
        $this->query = $search;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return Facet[]
     */
    public function getFacets(): array
    {
        return $this->facets;
    }

    /**
     * @param Facet[] $facets
     * @return self
     */
    public function setFacets($facets): self
    {
        $this->facets = $facets;

        return $this;
    }

    /**
     * @return Author[]
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param ListAuthors $authors
     * @return self
     */
    public function setAuthors(ListAuthors $authors): self
    {
        $this->authors = $authors->getAuthoritiesList();

        return $this;
    }

    /**
     * @return Notice[]
     */
    public function getNotices(): array
    {
        return $this->notices;
    }

    /**
     * @param ListNotices $notices
     * @return self
     */
    public function setNotices(ListNotices $notices): self
    {
        $this->notices = $notices->getNoticesList();

        return $this;
    }

    /**
     * @return Notice[]
     */
    public function getOnlineNotices(): array
    {
        return $this->onlineNotices;
    }

    /**
     * @param ListOnlineNotices $onlineNotices
     * @return self
     */
    public function setOnlineNotices(ListOnlineNotices $onlineNotices): self
    {
        $this->onlineNotices = $onlineNotices->getNoticesList();

        return $this;
    }

    /**
     * @return bool
     */
    public function hasQuery(): bool
    {
        return !empty($this->query);
    }

}
