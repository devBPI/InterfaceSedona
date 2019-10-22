<?php
declare(strict_types=1);

namespace App\Model\Search;

use App\WordsList;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Criteria
 * @package App\Model\Search
 * @JMS\XmlRoot("search-criterias")
 */
class Criteria
{
    const QUERY_NAME = 'criteria';

    const SIMPLE_SEARCH_KEYWORD = 'mot';
    const SIMPLE_SEARCH_TYPE = 'type';

    const ADVANCED_SEARCH_LABEL = 'advanced_search';
    const ADVANCED_SEARCH_OPERATOR = 'advanced_search_operator';
    const FIELD_LABEL = 'field';
    const TEXT_LABEL = 'text';

    const ADVANCED_KEYWORDS_LIMIT = 3;


    /**
     * @var string
     * @JMS\Type("string")
     */
    private $general;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $parcours;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("titre")
     */
    private $title;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("auteur")
     */
    private $author;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("realisateur")
     */
    private $realisator;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("sujet")
     */
    private $subject;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $theme;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("editeur")
     */
    private $editor;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("collection-namer")
     */
    private $collection;

    /**
     * @var integer
     * @JMS\Type("int")
     * @JMS\SerializedName("date-publication")
     */
    private $publicationDate;

    /**
     * @var integer
     * @JMS\Type("int")
     * @JMS\SerializedName("date-publication-start")
     */
    private $publicationDateStart;

    /**
     * @var integer
     * @JMS\Type("int")
     * @JMS\SerializedName("date-publication-fin")
     */
    private $publicationDateEnd;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("isbn-issn-numcommercial")
     */
    private $isbnIssnNumcommercial;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("indice-cote")
     */
    private $indiceCote;

    /**
     * @var self
     * @JMS\Type("App\Model\Search\Criteria")
     */
    private $and;
    /**
     * @var self
     * @JMS\Type("App\Model\Search\Criteria")
     */
    private $or;
    /**
     * @var self
     * @JMS\Type("App\Model\Search\Criteria")
     * @JMS\Accessor(getter="setNotSubCriteria")
     */
    private $not;

    /**
     * @var string
     * @JMS\SerializedName("not")
     * @JMS\Type("string")
     */
    private $notLabel;

    /**
     * @param $type
     * @param $keyword
     */
    public function setSimpleSearch($type, $keyword)
    {
        $this->$type = $keyword;
    }

    /**
     * @param array $request
     */
    public function setAdvancedSearch(array $request = [])
    {
        if (array_key_exists(self::QUERY_NAME, $request)) {
            foreach ($request[self::QUERY_NAME] as $name => $value) {
                $this->$name = $value;
            }
        }

        if (array_key_exists(self::ADVANCED_SEARCH_LABEL, $request)) {
            $this->setKeywords(
                $request[self::ADVANCED_SEARCH_LABEL],
                isset($request[self::ADVANCED_SEARCH_OPERATOR]) ? $request[self::ADVANCED_SEARCH_OPERATOR] : []
            );
        }
    }

    /**
     * @param array $keywordsArray
     * @param array $operators
     * @return Criteria
     */
    private function setKeywords(array $keywordsArray, array $operators = []): self
    {
        $this->setFieldByKeywordRow(current($keywordsArray));
        $key = key($keywordsArray);
        unset($keywordsArray[$key]);

        if (count($keywordsArray) === 0) {
            return $this;
        }

        if (count($operators) > 0) {
            $operator = current($operators);
            $key = key($operators);
            unset($operators[$key]);

            $t = new self();
            $this->$operator = $t->setKeywords($keywordsArray, $operators);
        }

        return $this;
    }

    /**
     * @param array $keywordRow
     */
    private function setFieldByKeywordRow(array $keywordRow): void
    {
        if (
            is_array($keywordRow) && array_key_exists(self::FIELD_LABEL, $keywordRow) &&
            array_key_exists(self::TEXT_LABEL, $keywordRow) &&
            trim($keywordRow[self::TEXT_LABEL]) !== ''
        ) {
            $type = $keywordRow[self::FIELD_LABEL];
            $this->$type = trim($keywordRow[self::TEXT_LABEL]);
        }
    }

    /**
     * @param bool $first
     * @return array
     */
    public function getKeywords($first = true): array
    {
        $keywords = [];
        foreach (WordsList::$words[WordsList::THEME_DEFAULT] as $field) {
            if (!empty($this->$field)) {
                if ($first) {
                    $keywords[] = [$field => $this->$field];
                } else {
                    $keywords[$field] = $this->$field;
                }
            }
        }

        foreach (WordsList::$operators as $operator) {
            if ($this->$operator instanceof Criteria) {
                $keywords[$operator] = $this->$operator->getKeywords(false);
            }
        }

        return $keywords;
    }

    /**
     * @param bool $withFields
     * @return array
     */
    public function getKeywordsTitles(bool $withFields = false): array
    {
        $keywords = [];
        foreach (WordsList::$words[WordsList::THEME_DEFAULT] as $field) {
            if (!empty($this->$field)) {
                if ($withFields) {
                    $keywords[$field] = $this->$field;
                } else {
                    $keywords[] = $this->$field;
                }
            }
        }

        foreach (WordsList::$operators as $operator) {
            if ($this->$operator instanceof Criteria) {
                $keywords = array_merge($keywords, $this->$operator->getKeywordsTitles($withFields));
            }
        }

        return $keywords;
    }

    /**
     * @return Criteria
     */
    public function setNotSubCriteria(): ?Criteria
    {
        if ($this->not instanceof Criteria) {
            $criteria = $this->not;
            $criteria->notLabel = 'true';
            $this->not = null;
            return $this->and = $criteria;
        }

        return $this->not;
    }

    /**
     * @return string
     */
    public function getParcours(): string
    {
        return $this->parcours;
    }

    /**
     * @param string|null $parcours
     * @return Criteria
     */
    public function setParcours(string $parcours=null):Criteria
    {
        $this->parcours = $parcours;

        return $this;
    }
}
