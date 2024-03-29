<?php
declare(strict_types=1);

namespace App\Model\Search;

use App\WordsList;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @JMS\SerializedName("collection")
     */
    private $collection;

    /**
     * @var string
     * @JMS\Type("int")
     * @JMS\SerializedName("date-publication")
     */
    private $publicationDate;

    /**
     * @var string
     * @JMS\Type("int")
     * @JMS\SerializedName("date-publication-debut")
     */
    private $publicationDateStart;

    /**
     * @var string
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
     * @var self[]
     * @JMS\Type("array<App\Model\Search\Criteria>")
     * @JMS\XmlList("search-criterias")
     */
    private $and;
    /**
     * @var self[]
     * @JMS\Type("array<App\Model\Search\Criteria>")
     * @JMS\XmlList("search-criterias")
     */
    private $or;
    /**
     * @var self[]|null
     * @JMS\Type("array<App\Model\Search\Criteria>")
     * @JMS\Accessor(getter="getNotSubCriteria", setter="setNotSubCriteria")
     */
    private $notCriteria;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $not;


    /**
     * @var string
     * @JMS\Type("string")
     */
    private $recompenses;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $droits;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("source-id")
     */
    private $sourceId;


    /**
     * @param string $type
     * @param string $keyword
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
                if (empty($value)) {
                    continue;
                }

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
            $this->$operator = [$t->setKeywords($keywordsArray, $operators)];
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
     * @param string $mode
     * @param bool $first
     * @return array
     */
    public function getKeywords($mode = SearchQuery::SIMPLE_MODE, $first = true): array
    {
        $keywords = [];
        $listKey = $mode === SearchQuery::SIMPLE_MODE ? WordsList::ALL : WordsList::FOR_KEYS;
        foreach (WordsList::$words[$listKey] as $field) {
            if (!empty($this->$field)) {
                if ($first) {
                    $keywords[] = [$field => $this->$field];
                } else {
                    $keywords[$field] = $this->$field;
                }
            }
        }

        foreach (WordsList::$operators as $operator) {
            $subCriteria = $this->getSubCriteriaOfOperator($operator);
            if ($subCriteria instanceof Criteria) {
                $subKeywords = $subCriteria->getKeywords($mode, false);
                if (!empty($subKeywords)) {
                    $keywords[$operator] = $subKeywords;
                }
            }
        }

        return $keywords;
    }

    /**
     * @param string $operator
     * @return Criteria|null
     */
    private function getSubCriteriaOfOperator(string $operator): ?Criteria
    {
        if (isset($this->$operator[0]) && $this->$operator[0] instanceof Criteria) {
            return $this->$operator[0];
        }

        return null;
    }

    /**
     * @param bool $withFields
     * @return array
     */
    public function getKeywordsTitles(bool $withFields = false): array
    {
        $keywords = [];
        foreach (WordsList::$words[WordsList::ALL] as $field) {
            if (!empty($this->$field)) {
                if ($withFields) {
                    $keywords[] = [$field =>  $this->$field];
                } else {
                    $keywords[] = $this->$field;
                }
            }
        }

        foreach (WordsList::$operators as $operator) {
            $subCriteria = $this->getSubCriteriaOfOperator($operator);
            if ($subCriteria instanceof Criteria) {
                $keywords = array_merge($keywords, $subCriteria->getKeywordsTitles($withFields));
            }
        }

        return $keywords;
    }
    /**
     * @return array
     */
    public function convertKeywordsToQueries(): array
    {
        $keywords = [];
        $words = WordsList::$words[WordsList::ALL];
        $words[] = 'sourceId';
        foreach ($words as $field) {
            if (!empty($this->$field)) {
                $keywords[] = ['text' => $this->$field, 'field' => $field];
            }
        }

        foreach (WordsList::$operators as $operator) {
            $subCriteria = $this->getSubCriteriaOfOperator($operator);
            if ($subCriteria instanceof Criteria) {
                $keywords = array_merge($keywords, $subCriteria->convertKeywordsToQueries());
            }
        }

        return $keywords;
    }
    /**
     * @return array
     */
    public function getOperators(): array
    {
        $operators = [];
        foreach (WordsList::$operators as $operator) {
            $subCriteria = $this->getSubCriteriaOfOperator($operator);
            if ($subCriteria instanceof Criteria) {
                $operators[] = $subCriteria->not ? 'notCriteria' : $operator;
                $operators = array_merge($operators, $subCriteria->getOperators());
            }
        }

        return $operators;
    }

    public function getFieldsWithOperator($fields, Criteria $criteria){
        $result = [];
        foreach ($fields  as $index =>  $field){
            foreach ($field as $key => $value){
                $result[$index]['value'] =$value;
                $result[$index]['field'] =$key;
                $result[$index]['operator'] ='';

                if ($index === 0){
                    $result[$index]['operator'] = '';
                    continue;
                }

                $andCriteria = $criteria->getAndCrteria();

                if($andCriteria instanceof Criteria){
                    if ($andCriteria->not) {
                        $result[$index]['operator'] = 'not';
                        continue;
                    }
                    if ($andCriteria->getValueOf($key) == $value){
                        $result[$index]['operator'] = 'and';
                        continue;
                    }
                    $andand = $andCriteria->getAndCrteria();
                    if ($andand instanceof Criteria && $andand->getValueOf($key) == $value) {
                        $result[$index]['operator'] = 'and';
                        continue;
                    }

                    $andor= $andCriteria->getOrCrteria();
                    if ($andor instanceof Criteria && $andor->getValueOf($key) ==   $value)
                    {
                        $result[$index]['operator'] = 'or';
                        continue;
                    }

                    $andnot=$andCriteria->getNotCrteria();
                    if ($andnot instanceof Criteria && $andnot->getValueOf($key) ==   $value)
                    {
                        $result[$index]['operator'] = 'not';
                        continue;
                    }
                }

                $or = $criteria->getOrCrteria();

                if($or instanceof Criteria){
                    if($or->getValueOf($key) == $value){
                        $result[$index]['operator'] = 'or';
                        continue;
                    }

                    $orand =$or->getAndCrteria();
                    if($orand instanceof Criteria  && $orand->getValueOf($key) == $value) {
                        $result[$index]['operator'] ='and';
                        continue;
                    }

                    $oror= $or->getOrCrteria();
                    if($oror instanceof Criteria && $oror->getValueOf($key) == $value) {
                        $result[$index]['operator'] = 'or';
                        continue;
                    }
                    $ornot= $or->getNotCrteria();
                    if ($ornot instanceof Criteria && $ornot->getValueOf($key) ==   $value)
                    {
                        $result[$index]['operator'] = 'not';
                        continue;
                    }
                }


                $not = $criteria->getNotCrteria();

                if($not instanceof Criteria){

                    if($not->getValueOf($key, $criteria) == $value){
                        $result[$index]['operator'] = 'not';
                        continue;
                    }

                    $notand = $not->getAndCrteria();
                    if($notand instanceof Criteria  && $notand->getValueOf($key, $criteria) == $value) {
                        $result[$index]['operator'] ='and';
                        continue;
                    }

                    $notor= $not->getOrCrteria();
                    if($notor instanceof Criteria && $notor->getValueOf($key) == $value) {
                        $result[$index]['operator'] = 'or';
                        continue;
                    }

                    $notnot =$not->getNotCrteria();
                    if($notnot instanceof Criteria && $notnot->getValueOf($key) == $value){
                        $result[$index]['operator'] = 'not';
                        continue;
                    }
                }

            }
        }


        return $result;
    }

    /**
     * @param $key
     * @return string|null
     */
    private function getValueOf($key, Criteria $criteria=null):?string{
        if(!empty($this->$key)) {
            return $this->$key;
        }
        elseif($criteria instanceof Criteria){
            return $criteria->$key;
        }
        return null;
    }
    /**
     * @param TranslatorInterface $translator
     * @param string $operator
     * @return string
     */
    public function getMyHistoryTitle(TranslatorInterface $translator, string $operator = '', $withParcours=true): string
    {
        $historyTitle = '';
        if ($withParcours && empty($operator) && !empty($this->getParcours()) && $this->getParcours() !== WordsList::THEME_DEFAULT) {
            $historyTitle = $translator->trans('header.title.'.$this->getParcours()). ' ';
        }

        foreach (WordsList::$words[WordsList::ALL] as $field) {
            if (!empty($this->$field)) {
                $historyTitle .= $this->$field. ' ('.$translator->trans('modal.advanced-search.keyword.type.'.$field).')';
            }
        }
        if (!empty($operator)) {
            if ($this->not) {
                $operator = 'notCriteria';
            }
            $historyTitle .= ' '.strtoupper($translator->trans('modal.advanced-search.keyword.group.'.$operator));
        }

        foreach (WordsList::$operators as $operator) {
            $subCriteria = $this->getSubCriteriaOfOperator($operator);
            if ($subCriteria instanceof Criteria) {
                $historyTitle .= ' '.$subCriteria->getMyHistoryTitle($translator, $operator);
            }
        }

        return $historyTitle;
    }

    /**
     * @return Criteria[]
     */
    public function getNotSubCriteria(): ?array
    {
        if (isset($this->notCriteria[0]) && $this->notCriteria[0] instanceof Criteria) {
            $criteria = $this->notCriteria[0];
            $criteria->not = 'true';
            $this->notCriteria = null;
            return $this->and = [$criteria];
        }

        return $this->notCriteria;
    }
    public function setNotSubCriteria(array $criteria): array
    {
        foreach ($criteria as $subCriteria) {
            $subCriteria->not = true;
            $this->and[] = $subCriteria;
        }
        return $criteria;
    }


    /**
     * @return string
     */
    public function getPublicationDate(): ?string
    {
        return $this->formatString($this->publicationDate);
    }
    /**
     * @return string
     */
    public function getPublicationDateStart(): ?string
    {
        return $this->formatString($this->publicationDateStart);
    }
    /**
     * @return string
     */
    public function getPublicationDateEnd(): ?string
    {
        return $this->formatString($this->publicationDateEnd);
    }

    /**
     * @param $value
     * @return null|string
     */
    private function formatString($value): ?string
    {
        if (!is_string($value)) {
            $value = (string) $value;
        }
        return $value;
    }

    /**
     * @return null|string
     */
    public function getParcours(): ?string
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

    /**
     * @return Criteria|null
     */
    public function getAndCrteria(): ?Criteria
    {
        return $this->and?$this->and[0]:null;
    }
    /**
     * @return Criteria|null
     */
    public function getOrCrteria(): ?Criteria
    {
        return $this->or?$this->or[0]:null;
    }
    /**
     * @return Criteria|null
     */
    public function getNotCrteria(): ?Criteria
    {
        return $this->notCriteria?$this->notCriteria[0]:null;
    }
}
