<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 28/10/19
 * Time: 16:59
 */
namespace App\Model\Traits;

use JMS\Serializer\Annotation as JMS;

trait IndiceAndAuthorityTrait
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("formRetenue")
     */
    private $formAdopted;

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
     * @JMS\SerializedName("activitesPrincipales")
     * @JMS\XmlList("activitePrincipale")
     */
    private $principalActivities;

    /**
     * @return string|null
     */
    public function getFormAdopted(): ?string
    {
        return $this->formAdopted;
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
    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    /**
     * @return null|string
     */
    public function getBirthLocation(): ?string
    {
        return $this->birthLocation;
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
    public function getPrincipalActivities(): array
    {
        return $this->principalActivities;
    }

}
