<?php
declare(strict_types=1);
namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Authority
 * @package App\Model
 */
class Authority implements AuthorityInterface
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("permalink")
     */
    private $permalink;
    /**
     * @var string
     * @JMS\Type("string")
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
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("type")
     */
    private $type;
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
     * @return string
     */
    public function getBirthLocation(): string
    {
        return $this->birthLocation;
    }

    /**
     * @return string
     */
    public function getDeathLocation(): string
    {
        return $this->deathLocation;
    }
    /**
     * @var string
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
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("isni")
     */
    private $isni;
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("urlOrigine")
     */
    private $originalUrl;

    /**
     * @return string
     */
    public function getId(): string
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
     * @return string
     */
    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    /**
     * @return string
     */
    public function getDeathDate(): string
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
     * @return string
     */
    public function getIsni(): string
    {
        return $this->isni;
    }

    /**
     * @return null|string
     */
    public function getOriginalUrl(): ?string
    {
        return $this->originalUrl;
    }

    /**
     * @return string
     */
    public function getPermalink(): string
    {
        return $this->permalink;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}

