<?php

namespace App\Model;


use App\Model\Interfaces\NoticeInterface;
use App\Model\Interfaces\SearchResultInterface;
use App\Model\Search\Criteria;
use App\Model\Traits\SearchResultTrait;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Results
 * @package App\Model
 */
class Results implements SearchResultInterface
{
    use SearchResultTrait;
    /**
     * @var Facets
     * @JMS\Type("App\Model\Facets")
     */
    private $facets;

    /**
     * @var array|RankedAuthority[]
     * @JMS\Type("array<App\Model\RankedAuthority>")
     * @JMS\SerializedName("authorities-list")
     * @JMS\XmlList(entry="ranked-authority-indice-cdu")
     */
    private $authoritiesList = [];

    /**
     * @var array|Authority[]
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("sujets-liees-list")
     * @JMS\XmlList(entry="sujet-liee")
     */
    private $linkedSubjects = [];

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
    /**
     * @var SuggestionList
     * @JMS\Type("App\Model\SuggestionList")
     * @JMS\SerializedName("suggestions-list")
     */
    private $suggestionList;
    /**
     * @var int
     * @JMS\Type("int")
     * @JMS\SerializedName("page")
     */
    private $page;
    /**
     * @var int
     * @JMS\Type("int")
     * @JMS\SerializedName("num-pages")
     */
    private $pageTotal;
    /**
     * @var int
     * @JMS\Type("int")
     */
    private $rows;

    /**
     * @return RankedAuthority[]|array
     */
    public function getAuthoritiesList()
    {
        return $this->authoritiesList;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPageTotal(): int
    {
        return $this->pageTotal;
    }

    /**
     * @return RankedAuthority[]
     */
    public function getAuthors(): array
    {
        return $this->authoritiesList;
    }

    /**
     * @return NoticeInterface|null
     */
    public function getMainAuthor()
    {
        if (count($this->authoritiesList) === 0) {
            return null;
        }

        return $this->authoritiesList[0]->getAuthor();
    }

    /**
     * @return array
     */
    public function getOtherAuthors(): array
    {
        return array_map(function (RankedAuthority $item) {
            return $item->getAuthor();
        }, array_slice($this->authoritiesList, 1));
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
     * @return Authority[]|array
     */
    public function getLinkedSubjects(): array
    {
        return $this->linkedSubjects;
    }

    /**
     * @return SuggestionList
     */
    public function getSuggestionList(): SuggestionList
    {
        return $this->suggestionList;
    }

    /**
     * @param Criteria|null $criteria
     * @return Results
     */
    public function setCriteria(Criteria $criteria=null): Results
    {
        $this->criteria = $criteria;
        return $this;
    }

    /**
     * @return Facets
     */
    public function getFacets(): Facets
    {
        return $this->facets;
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

}

