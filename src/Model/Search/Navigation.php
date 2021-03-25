<?php

namespace App\Model\Search;


use App\Model\AbstractListNotices;
use App\Model\Exception\NoResultException;
use App\Model\Interfaces\NavigationInterface;

/**
 * Class ListNavigation
 * @package App\Model\Search
 */
final class Navigation
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @var int|null
     */
    private $total;

    /**
     * @var array
     */
    private $list = [];

    /**
     * @var int|null
     */
    private $currentIndex;

    /**
     * @var NavigationLink|null
     */
    private $previousLink;

    /**
     * @var NavigationLink|null
     */
    private $nextLink;
    /**
     * Navigation constructor.
     * @param string $hash
     * @param NavigationInterface $results
     */
    public function __construct(string $hash, NavigationInterface $results)
    {
        $this->hash = $hash;
        $this->total = $results->getTotalCount();
        $this->list = $this->getPermalinkList($results);
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return NavigationLink|null
     */
    public function getPreviousLink(): ?NavigationLink
    {
        return $this->previousLink;
    }

    /**
     * @return NavigationLink|null
     */
    public function getNextLink(): ?NavigationLink
    {
        return $this->nextLink;
    }

    /**
     * @return int|null
     */
    public function getCurrentIndex(): ?int
    {
        return $this->currentIndex;
    }

    /**
     * @return int|null
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }

    /**
     * @param NavigationInterface $notices
     * @return array
     */
    private function getPermalinkList(NavigationInterface $notices): array
    {
        $rowIndex = ($notices->getPage() - 1) * $notices->getRows();
        $list = [];
        foreach ($notices->getNoticesList() as $index => $notice) {
            $totalIndex = $rowIndex + $index + 1;
            $list[$totalIndex] = new NavigationLink($notice->getPermalink(), $notice->getClassName());
        }

        return $list;
    }

    /**
     * @param string $permalink
     */
    public function setCurrentIndex(string $permalink): void
    {
        $this->currentIndex = $this->getIndexOfList($permalink);
    }



    /**
     * @param string $permalink
     * @return int|null
     */
    private function getIndexOfList(string $permalink): ?int
    {
        foreach ($this->list as $index => $notice) {
            if ($notice->getPermalink() === $permalink) {
                return $index;
            }
        }

        return null;
    }

    /**
     * @param int $index=
     * @return NavigationLink
     */
    private function checkIfIndexExist(int $index): NavigationLink
    {
        if (!array_key_exists($index, $this->list)) {
            throw new NoResultException();
        }

        return $this->list[$index];
    }

    /**
     * @param AbstractListNotices $notices
     */
    public function addPermalinks(AbstractListNotices $notices): void
    {
        $this->list += $this->getPermalinkList($notices);
    }

    public function setPreviousLink()
    {
        if ($this->currentIndex > 1) {
            $this->previousLink = $this->checkIfIndexExist($this->currentIndex - 1);
        }
    }
    public function setNextLink()
    {
        if ($this->currentIndex < $this->total) {
            $this->nextLink = $this->checkIfIndexExist($this->currentIndex + 1);
        }
    }
}
