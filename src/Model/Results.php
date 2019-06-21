<?php

namespace App\Model;


use JMS\Serializer\Annotation as JMS;

/**
 * Class Results
 * @package App\Model
 */
class Results
{
    /**
     * @var string
     * @JMS\Exclude()
     */
    private $query = 'Maupassant';

    /**
     * @var Facets
     * @JMS\Type("App\Model\Facets")
     */
    private $facets;

    /**
     * @var array|RankedAuthority[]
     * @JMS\Type("array<App\Model\RankedAuthority>")
     * @JMS\SerializedName("relevant-authorities")
     * @JMS\XmlList(entry="authorities-list")
     */
    private $rankedAuthorities = [];

//    /**
//     * @var array|Authority[]
//     * @JMS\Type("array<App\Model\Authority>")
//     * @JMS\SerializedName("relevant-authorities")
//     * @JMS\XmlList(entry="ranked-authority-indice-cdu")
//     */
//    private $linked_subjects = [];

    /**
     * @var Notices
     * @JMS\Type("App\Model\Notices")
     */
    private $notices;

    /**
     * @var NoticesOnline
     * @JMS\Type("App\Model\NoticesOnline")
     */
    private $noticesOnline;

//    /**
//     * SearchResult constructor.
//     * @param string $search
//     */
//    public function __construct(string $search)
//    {
//        $this->query = $search;
//    }

    /**
     * @return string
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }
    /**
     * @return bool
     */
    public function hasQuery(): bool
    {
        return !empty($this->query);
    }

    /**
     * @return Authority[]
     */
    public function getAuthors(): array
    {
        return $this->rankedAuthorities;
    }

    /**
     * @return Authority|null
     */
    public function getMainAuthor(): ?Authority
    {
        if (count($this->rankedAuthorities) === 0) {
            return null;
        }

        return $this->rankedAuthorities[0]->getAuthor();
    }

    /**
     * @return Authority[]
     */
    public function getOtherAuthors(): array
    {
        return array_slice($this->rankedAuthorities, 1);
    }

    /**
     * @return Notices
     */
    public function getNotices(): Notices
    {
        return $this->notices;
    }

    /**
     * @return NoticesOnline
     */
    public function getNoticesOnline(): NoticesOnline
    {
        return $this->noticesOnline;
    }

    /**
     * @return Facets
     */
    public function getFacets(): Facets
    {
        return $this->facets;
    }

}
