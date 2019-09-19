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
    const SORT_DEFAULT = 'DEFAULT';
    const SORT = [
        'default' => self::SORT_DEFAULT,
        'tile_a_z' => 'TITRE_A_Z',
        'tile_z_a' => 'TITRE_Z_A',
        'author_a_z' => 'AUTHEUR_A_Z',
        'author_z_a' => 'AUTHEUR_Z_A',
        'older' => 'OLDER',
        'younger' => 'YOUNGER',
    ];
    const ROWS_DEFAULT = 15;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $general;

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
     * @param array $request
     */
    public function __construct(array $request = [])
    {
        if (
            array_key_exists(WordsList::ADVANCED_SEARCH_ACTION, $request) &&
            $request[WordsList::ADVANCED_SEARCH_ACTION] === WordsList::CLICKED
        ) {
            foreach ($request[self::QUERY_NAME] as $name => $value) {
                $this->$name = $value;
            }

            $this->setKeywords(
                $request[WordsList::ADVANCED_SEARCH_LABEL],
                $request[WordsList::ADVANCED_SEARCH_OPERATOR]
            );
        } elseif (
            array_key_exists(WordsList::SIMPLE_SEARCH_LABEL, $request) &&
            $request[WordsList::SIMPLE_SEARCH_LABEL] !== null
        ) {
            $this->setFieldByKeywordRow($request[WordsList::SIMPLE_SEARCH_LABEL]);
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
