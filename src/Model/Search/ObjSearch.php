<?php

namespace App\Model\Search;

use App\Model\Results;

/**
 * Class ObjSearch
 * @package App\Model\Search
 */
final class ObjSearch
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $context;

    /**
     * @var Results
     */
    private $results;

    /**
     * @var array
     */
    private $keywords;
    /**
     * @var SearchQuery
     */
    private $searchQuery;

    /**
     * ObjSearch constructor.
     *
     * @param SearchQuery $searchQuery
     */
    public function __construct(SearchQuery $searchQuery)
    {
        $this->searchQuery = $searchQuery;
        $this->keywords = $searchQuery->getCriteria()->getKeywords();
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $hash
     * @param string $serialize
     */
    public function setContext(string $hash, string $serialize)
    {
        $this->context = [
            'hash' => $hash,
            'config' => $serialize
        ];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return null|string
     */
    public function getContextConfig(): ?string
    {
        if (is_array($this->context) && array_key_exists('config', $this->context)) {
            return $this->context['config'];
        }

        return null;
    }
    /**
     * @return null|string
     */
    public function getContextToken(): ?string
    {
        if (is_array($this->context) && array_key_exists('hash', $this->context)) {
            return $this->context['hash'];
        }

        return null;
    }

    /**
     * @return Results
     */
    public function getResults(): Results
    {
        return $this->results;
    }

    /**
     * @return array|null
     */
    public function getSimpleSearchKeyword(): ?string
    {
        if ($this->isSimpleMode()) {
            return array_values($this->keywords[0])[0];
        }

        return null;
    }
    /**
     * @return array|null
     */
    public function getSimpleSearchType(): ?string
    {
        if ($this->isSimpleMode()) {
            return array_keys($this->keywords[0])[0];
        }

        return null;
    }

    /**
     * @return bool
     */
    private function isSimpleMode(): bool
    {
        return is_array($this->keywords) && count($this->keywords) === 1;
    }

    /**
     * @return array
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    /**
     * @param Results $results
     * @return ObjSearch
     */
    public function setResults(Results $results): self
    {
        $this->results = $results;
        return $this;
    }

    /**
     * @return array
     */
    public function getSearchFilters(): array
    {
        return $this->searchQuery->getFacets()->getAttributes();
    }

    /**
     * @return array
     */
    public function getAdvancedCriteria(): array
    {
        if ($this->isSimpleMode()) {
            return [];
        }

        $criteria = [];
        foreach ($this->searchQuery->getCriteria()->getKeywordsTitles(true) as $field => $keyword) {
            $criteria[$field] = $keyword;
        }

        foreach ($this->getSearchFilters() as $filters) {
            foreach ($filters as $filter) {
                $criteria[] = $filter;
            }
        }

        return $criteria;
    }
}
