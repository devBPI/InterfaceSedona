<?php

namespace App\Model\Search;

use App\WordsList;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Criteria
 * @package App\Model\Search
 * @JMS\XmlRoot("search-criterias")
 */
class Criteria
{
    const QUERY_NAME = 'criteria';

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $general;

    /**
     * @var string
     * @JMS\Type("number")
     */
    private $rows;

    /**
     * @var string
     * @JMS\Type("number")
     */
    private $page;

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
     * @JMS\Type("number")
     * @JMS\SerializedName("date-publication")
     */
    private $publicationDate;

    /**
     * @var integer
     * @JMS\Type("number")
     * @JMS\SerializedName("date-publication-start")
     */
    private $publicationDateStart;

    /**
     * @var integer
     * @JMS\Type("number")
     * @JMS\SerializedName("date-publication-fin")
     */
    private $publicationDateEnd;


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
     * Criteria constructor.
     * @param Request $request
     */
    public function __construct(Request $request = null)
    {
        if ($request instanceof Request) {
            if ($request->get(WordsList::ADVANCED_SEARCH_ACTION) === WordsList::CLICKED) {
                foreach ($request->get(self::QUERY_NAME, []) as $name => $value) {
                    $this->$name = $value;
                }

                $this->setKeywords(
                    $request->get(WordsList::ADVANCED_SEARCH_LABEL, []),
                    $request->get(WordsList::ADVANCED_SEARCH_OPERATOR, [])
                );
            } elseif ($request->get(WordsList::SIMPLE_SEARCH_LABEL, null) !== null) {
                $this->setFieldByKeywordRow($request->get(WordsList::SIMPLE_SEARCH_LABEL, []));
            }
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
            is_array($keywordRow) && array_key_exists(WordsList::FIELD_LABEL, $keywordRow) &&
            array_key_exists(WordsList::TEXT_LABEL, $keywordRow)
        ) {
            $type = $keywordRow[WordsList::FIELD_LABEL];
            $this->$type = $keywordRow[WordsList::TEXT_LABEL];
        }
    }
}
