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
     * @return Authority[]
     */
    public function getAuthors(): array
    {
        return $this->authoritiesList;
    }

    /**
     * @return AuthorityInterface|null
     */
    public function getMainAuthor(): ?AuthorityInterface
    {
        if (count($this->authoritiesList) === 0) {
            return null;
        }

        return $this->authoritiesList[0]->getAuthor();
    }

    /**
     * @return AuthorityInterface[]
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
     * @return Facets
     */
    public function getFacets(): Facets
    {
        return $this->facets;
    }

    /**
     * @return string[]|array
     */
    public function getLinkedSubjects(): array
    {
        return $this->linkedSubjects;
    }

}
