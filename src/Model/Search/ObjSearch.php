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
    CONST PARAM_PARCOURS_NAME = 'parcours';
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
        $this->keywords = $searchQuery->getCriteria()->getKeywords($searchQuery->getMode());
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
     * @return string
     */
    public function getKeyword(): string
    {
        if (isset($this->keywords[0])) {
            return array_values($this->keywords[0])[0];
        }

        return '';
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
    public function getSearchFacets(): array
    {
        return $this->searchQuery->getFacets()->getAttributes();
    }

    /**
     * @return array
     */
    public function getSearchFilters(): array
    {
        return $this->searchQuery->getFilters()->getAttributes();
    }

    /**
     * @return Criteria|null
     */
    public function getCriteria(): ?Criteria
    {
        return $this->searchQuery->getCriteria();
    }

    public function getAdvancedCriteriaWithOperator(){



        $criteria = $this->getCriteria()->getFieldsWithOperator( $this->getCriteria()->getKeywordsTitles(true),$this->getCriteria());
        if (
            $this->searchQuery->getCriteria()->getPublicationDateStart() ||
            $this->searchQuery->getCriteria()->getPublicationDateEnd()
        ) {
            $criteria[] =
                ['value'=> $this->searchQuery->getCriteria()->getPublicationDateStart().' - '.
                    $this->searchQuery->getCriteria()->getPublicationDateEnd() ,
                    'field'=>'date_publishing',
                    'operator'=>''
                ]

            ;
        }

        foreach ($this->getSearchFilters() as $name => $values) {
            if ($name === 'date_publishing' && is_array($values)) {
                $values = min($values).' - '.max($values);
            }

            if (is_array($values)) {
                $values = implode(', ', $values);
            }

            $criteria[] =  ['value'=> $values,
                'field'=>$name,
                'operator'=>''
            ];
        }


        foreach ($this->getSearchFacets() as $name => $values) {
            if ($name === 'date_publishing' && is_array($values)) {
                $values = min($values).' - '.max($values);
            }

            if (is_array($values)) {
                $values = implode(', ', $values);
            }

            $criteria[] = ['value'=> $values,
                'field'=>$name,
                'operator'=>''
            ];
        }

        return $criteria;
    }

    /**
     * @return array
     */
    public function getAdvancedCriteria(): array
    {
        $criteria = $this->searchQuery->getCriteria()->getKeywordsTitles(true);

        if (
            $this->searchQuery->getCriteria()->getPublicationDateStart() ||
            $this->searchQuery->getCriteria()->getPublicationDateEnd()
        ) {
            $criteria[] = ['date_publishing' =>
                $this->searchQuery->getCriteria()->getPublicationDateStart().' - '.
                $this->searchQuery->getCriteria()->getPublicationDateEnd()
            ];
        }

        foreach ($this->getSearchFilters() as $name => $values) {
            if ($name === 'date_publishing' && is_array($values)) {
                $values = min($values).' - '.max($values);
            }

            if (is_array($values)) {
                $values = implode(', ', $values);
            }

            $criteria[] = [($name) => $values];
        }

        foreach ($this->getSearchFacets() as $name => $values) {
            if ($name === 'date_publishing' && is_array($values)) {
                $values = min($values).' - '.max($values);
            }

            if (is_array($values)) {
                $values = implode(', ', $values);
            }

            $criteria[] = [($name) => $values];
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

    /**
     * @return bool
     */
    public function isAdvancedMode(): bool
    {
        return $this->advancedMode;
    }

}
