<?php

namespace App\Model\Search;

use App\Model\Results;

/**
 * Class ObjSearch
 * @package App\Model\Search
 */
final class ObjSearch
{
    CONST PARAM_REQUEST_NAME = 'searchToken';

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $context;

    /**
     * @var boolean
     */
    private $advancedMode;

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
        $this->advancedMode = $searchQuery->getMode() === SearchQuery::ADVANCED_MODE;
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
        if (!$this->advancedMode && isset($this->keywords[0])) {
            return array_values($this->keywords[0])[0];
        }

        return null;
    }
    /**
     * @return array|null
     */
    public function getSimpleSearchType(): ?string
    {
        if (!$this->advancedMode && isset($this->keywords[0])) {
            return array_keys($this->keywords[0])[0];
        }

        return null;
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
     * @return Criteria|null
     */
    public function getCriteria(): ?Criteria
    {
        return $this->searchQuery->getCriteria();
    }

    /**
     * @return array
     */
    public function getAdvancedCriteria(): array
    {
        if (!$this->advancedMode) {
            return [];
        }

        $criteria = [];
        foreach ($this->searchQuery->getCriteria()->getKeywordsTitles(true) as $field => $keyword) {
            $criteria[$field] = $keyword;
        }

        foreach ($this->getSearchFilters() as $name => $values) {
            if ($name === 'date_publishing' && is_array($values)) {
                $values = min($values).' - '.max($values);
            }

            if (is_array($values)) {
                $values = implode(',', $values);
            }

            $criteria[$name] = $values;
        }

        return $criteria;
    }

    /**
     * @return int
     */
    public function getGlobalIndex(): int
    {
        return (($this->searchQuery->getPage() - 1) * $this->searchQuery->getRows()) + 1;
    }

}
