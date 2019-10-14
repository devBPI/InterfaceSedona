<?php
declare(strict_types=1);

namespace App\Model;

use App\Model\Interfaces\NoticeInterface;
use App\Model\Traits\NoticeTrait;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Authority
 * @package App\Model
 */
class Authority implements NoticeInterface
{
    use NoticeTrait;

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
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("formRetenue")
     */
    private $formAdopted;


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
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("dateNaissance")
     */
    private $birthDate;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("lieuNaissance")
     */
    private $birthLocation;

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
     * @JMS\SerializedName("activitesPrincipales")
     * @JMS\XmlList("activitePrincipale")
     */
    private $principalActivities;
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
     * @JMS\SerializedName("autresNoms")
     * @JMS\XmlList("autreNom")
     */
    private $otherNames;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("employePour")
     */
    private $employedfor;
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
     * @JMS\SerializedName("notesClassement")
     * @JMS\XmlList("noteClassement")
     */
    private $classifiedNote;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("pays")
     * @JMS\XmlList("pays")
     */
    private $countries;

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("langues")
     * @JMS\XmlList("langue")
     */
    private $languages;

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
    public function getBirthLocation(): ?string
    {

        return $this->birthLocation;
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
     * @return null|string
     */
    public function getFormAdopted(): ?string
    {
        return $this->formAdopted;
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
    public function getPrincipalActivities(): array
    {
        return $this->principalActivities;
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
    public function getOtherNames(): array
    {
        return $this->otherNames;
    }

    /**
     * @return null|string
     */
    public function getEmployedfor(): ?string
    {
        return $this->employedfor;
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
    public function getClassifiedNote(): array
    {
        return $this->classifiedNote;
    }

    /**
     * @return array
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->languages;
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
     *
     * @return string
     */
    public function getTitle(): string
    {
        if ($this->getName()){
            return $this->getName();
        }

        return "Notice d'autorité sans nom";
    }
}

