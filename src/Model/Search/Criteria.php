<?php
declare(strict_types=1);
namespace App\Model\Search;

use App\Model\DTO\ArrayConstructibleDTO;
use App\WordsList;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Criteria
 * @package App\Model\Search
 * @JMS\XmlRoot("search-criterias")
 */
class Criteria implements ToJsonInterface, FromArrayInterface
{
    const MANDATORY_KEYS = [
        'and',
        'or',
        'author',
        'collection' ,
        'editor',
        'general' ,
        'page' ,
        'rows',
        'title' ,
        'realisateur',
        'subject' ,
        'theme',
        'publicationDate' ,
        'publicationDateStart',
        'publicationDateEnd' ,
        'sort',
    ];
    const QUERY_NAME    = 'criteria';
    const SORT_DEFAULT ='DEFAULT' ;
    const SORT = [
        'default'    => self::SORT_DEFAULT,
        'tile_a_z'   =>'TITRE_A_Z',
        'tile_z_a'   =>'TITRE_Z_A',
        'author_a_z' =>'AUTHEUR_A_Z',
        'author_z_a' =>'AUTHEUR_Z_A',
        'older'      =>'OLDER',
        'younger'    =>'YOUNGER',
    ];
    const ROWS_DEFAULT = 15;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $general;

    /**
     * @var int
     * @JMS\Type("int")
     */
    private $rows;
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $sort;

    /**
     * @var string
     * @JMS\Type("int")
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

    /**
     * @param array $array
     * @return Criteria|mixed
     */
    public static function fromArray($array){
        $criteria =  new Criteria();

        if (!is_array($array) ||  count($array)==0){
            return $criteria;
        }

        ArrayConstructibleDTO::checkRequiredKeys(self::MANDATORY_KEYS, $array);

        $criteria
            ->setAnd(self::fromArray($array['and']))
            ->setAuthor($array ['author'])
            ->setCollection($array ['collection'])
            ->setEditor($array ['editor'])
            ->setGeneral($array ['general'])
            ->setOr(self::fromArray($array ['or']))
            ->setPage($array ['page'])
            ->setRows($array ['rows'])
            ->setTitle($array ['title'])
            ->setRealisator($array ['realisateur'])
            ->setSubject($array ['subject'])
            ->setTheme($array ['theme'])
            ->setPublicationDate($array ['publicationDate'])
            ->setPublicationDateStart($array ['publicationDateStart'])
            ->setPublicationDateEnd($array ['publicationDateEnd'])
            ->setSort($array ['sort'])
        ;

        return $criteria;
    }

    public function __toArray(){

        return [
            'and' => $this->getAnd()?$this->getAnd()->__toArray():[],
            'or' => $this->getOr()?$this->getOr()->__toArray():[],
            'author' => $this->getAuthor(),
            'collection' => $this->getCollection(),
            'editor' => $this->getEditor(),
            'general' => $this->getGeneral(),
            'page' => $this->getPage()??1,
            'rows' => $this->getRows(),
            'title' => $this->getTitle(),
            'realisateur' => $this->getRealisator(),
            'subject' => $this->getSubject(),
            'theme' => $this->getTheme(),
            'publicationDate' => $this->getPublicationDate(),
            'publicationDateStart' => $this->getPublicationDateStart(),
            'publicationDateEnd' => $this->getPublicationDateEnd(),
            'sort'=> $this->getSort()
        ]
            ;
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows??Criteria::ROWS_DEFAULT;
    }

    /**
     * @param int|null $rows
     * @return Criteria
     */
    public function setRows(int $rows=null): Criteria
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @param int|null $page
     * @return Criteria
     */
    public function setPage(int $page=1): Criteria
    {

        $this->page = $page;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return Criteria
     */
    public function setTitle(string $title=null): Criteria
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string|null $author
     * @return Criteria
     */
    public function setAuthor(string $author=null): Criteria
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getRealisator(): ?string
    {
        return $this->realisator;
    }

    /**
     * @param string|null $realisator
     * @return Criteria
     */
    public function setRealisator(string $realisator=null): Criteria
    {
        $this->realisator = $realisator;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string|null $subject
     * @return Criteria
     */
    public function setSubject(string $subject=null): Criteria
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTheme(): ?string
    {
        return $this->theme;
    }

    /**
     * @param string|null $theme
     * @return Criteria
     */
    public function setTheme(string $theme=null): Criteria
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEditor(): ?string
    {
        return $this->editor;
    }

    /**
     * @param string|null $editor
     * @return Criteria
     */
    public function setEditor(string $editor=null): Criteria
    {
        $this->editor = $editor;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCollection(): ?string
    {
        return $this->collection;
    }

    /**
     * @param string|null $collection
     * @return Criteria
     */
    public function setCollection(string $collection=null): Criteria
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPublicationDate(): ?int
    {
        return $this->publicationDate;
    }

    /**
     * @param int|null $publicationDate
     * @return Criteria
     */
    public function setPublicationDate(int $publicationDate=null): Criteria
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPublicationDateStart(): ?int
    {
        return $this->publicationDateStart;
    }

    /**
     * @param int|null $publicationDateStart
     * @return Criteria
     */
    public function setPublicationDateStart(int $publicationDateStart=null): Criteria
    {
        $this->publicationDateStart = $publicationDateStart;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPublicationDateEnd(): ?int
    {
        return $this->publicationDateEnd;
    }

    /**
     * @param int|null $publicationDateEnd
     * @return Criteria
     */
    public function setPublicationDateEnd(int $publicationDateEnd=null): Criteria
    {
        $this->publicationDateEnd = $publicationDateEnd;
        return $this;
    }

    /**
     * @return Criteria|null
     */
    public function getAnd(): ?Criteria
    {
        return $this->and;
    }

    /**
     * @param Criteria|null $and
     * @return Criteria
     */
    public function setAnd(Criteria $and=null): Criteria
    {
        $this->and = $and;
        return $this;
    }


    /**
     * @return Criteria|null
     */
    public function getOr(): ?Criteria
    {
        return $this->or;
    }

    /**
     * @param Criteria|null $or
     * @return Criteria
     */
    public function setOr(Criteria $or=null): Criteria
    {
        $this->or = $or;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGeneral(): ?string
    {
        return $this->general;
    }

    /**
     * @param string|null $general
     * @return Criteria|null
     */
    public function setGeneral(string $general=null): ?Criteria
    {
        $this->general = $general;
        return $this;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return \json_encode($this->__toArray());

    }

    /**
     * @param string|null $sort
     * @return $this
     */
    public function setSort(string $sort=null)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSort(): ?string
    {
        return $this->sort;
    }


}
