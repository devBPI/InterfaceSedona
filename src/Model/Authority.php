<?php
declare(strict_types=1);

namespace App\Model;

use App\Model\Interfaces\NoticeInterface;
use App\Model\Interfaces\RecordInterface;
use App\Model\Traits\BreadcrumbTrait;
use App\Model\Traits\IndiceAndAuthorityTrait;
use App\Model\Traits\NoticeTrait;
use App\Model\Traits\PrintTrait;
use App\Request\ParamConverter\BpiConverterInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Authority
 * @package App\Model
 */
final class Authority implements NoticeInterface, RecordInterface, BpiConverterInterface
{
    private const DOC_TYPE = 'authority';
    const BREAD_CRUMB_NAME = 'authority';

    use NoticeTrait, IndiceAndAuthorityTrait, BreadcrumbTrait, PrintTrait;

    /**
     * @var int
     * @JMS\Type("int")
     * @JMS\SerializedName("id")
     */
    private $id;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("formeRetenue")
     */
    private $name;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("formesParalleles")
     * @JMS\XmlList("formeParallele")
     */
    private $formParalella;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("genresMusicaux")
     * @JMS\XmlList("genreMusical")
     */
    private $musicalKinds;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("dates")
     * @JMS\XmlList("date")
     */
    private $dates;


    /**
     * @var string|null
     * @JMS\Type("string")
     * @JMS\SerializedName("dateMort")
     */
    private $deathDate;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("lieuMort")
     */
    private $deathLocation;


    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("notes")
     * @JMS\XmlList("note")
     */
    private $notes;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("formesAssociees")
     * @JMS\XmlList("formeAssociee")
     */
    private $associetedForm;


    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("origines")
     * @JMS\XmlList("origine")
     */
    private $origins;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("resumes")
     * @JMS\XmlList("resume")
     */
    private $resumes;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("urlOrigine")
     */
    private $originalUrl;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("isni")
     */
    private $isni;

    /**
     * @return null|string
     */
    public function getIsni(): ?string
    {
        return $this->isni;
    }

    /**
     * @return null|string
     */
    public function getDeathLocation(): ?string
    {
        return $this->deathLocation;
    }
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @return array
     */
    public function getFormParalella(): array
    {
        return $this->formParalella;
    }

    /**
     * @return array
     */
    public function getMusicalKinds(): array
    {
        return $this->musicalKinds;
    }

    /**
     * @return array
     */
    public function getDates(): array
    {
        return $this->dates;
    }

    /**
     * @return null|string
     */
    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    /**
     * @return null|string
     */
    public function getDeathDate(): ?string
    {
        return $this->deathDate;
    }


    /**
     * @return array
     */
    public function getNotes(): array
    {
        return $this->notes;
    }

    /**
     * @return array
     */
    public function getAssocietedForm(): array
    {
        return $this->associetedForm;
    }

    /**
     * @return array
     */
    public function getOrigins(): array
    {
        return $this->origins;
    }



    /**
     * @return null|string
     */
    public function getOriginalUrl(): ?string
    {
        return $this->originalUrl;
    }

    /**
     * @return array
     */
    public function getResumes(): array
    {
        return $this->resumes;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        if ($this->getName()){
            return $this->getName();
        }

        return "Notice d'autorité sans nom";
    }

    /**
     * @return bool
     */
    public function isIndice(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getDocType(): string
    {
        return self::DOC_TYPE;
    }

    public function getPrintDates(): ?string
    {
        return $this->concatenateData($this->birthDate, $this->deathDate);
    }

    public function getPrintBirthData(): ?string
    {
        return $this->concatenateData($this->birthDate, $this->birthLocation);
    }
    public function getPrintDeathData(): ?string
    {
        return $this->concatenateData($this->deathDate, $this->deathLocation);
    }
    public function getPrintTitle(): string
    {
        return $this->getTitle();
    }

    public function getClassName(): string
    {
        return self::class;
    }
}

