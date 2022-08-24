<?php
declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Link
 * @package App\Model
 */
class Link
{
    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("object-type")
     */
    private $objectType;

    /**
     * @see Renvoyer par une notice >   /fr/notice-bibliographique/ark:/34201/nptfl0001260012
     * @var string
     * @JMS\Type("string")
     */
    private $title;

    /**
     * @see Renvoyer par le resultat >   /fr/recherche?mot=Moustaki%2C+Georges&type=general&action=
     * @deprecated
     * @var string
     * @JMS\Type("string")
     */
    private $titre;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $right;

    /**
     * @var string
     * @JMS\SerializedName("disponibilite")
     * @JMS\Type("string")
     */
    private $availability;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $media;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("used-profil")
     */
    private $usedProfil;

    /**
     * @var string
     * @JMS\SerializedName("accessDateFirstIssueOnline")
     *
     * @JMS\Type("string")
     */
    private $accessDateFirstIssueOnline;
    /**
     * @var string
     *
     * @JMS\SerializedName("accessDateLastIssueOnline")
     *
     * @JMS\Type("string")
     */
    private $accessDateLastIssueOnline;
    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("accessNumFirstIssueOnline")
     */
    private $accessNumFirstIssueOnline;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("accessNumLastIssueOnline")
     */
    private $accessNumLastIssueOnline;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("accessNumFirstVolOnline")
     */
    private $accessNumFirstVolOnline;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("accessNumLastVolOnline")
     */
    private $accessNumLastVolOnline;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("cote")
     */
    private $cote;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("localisation")
     */
    private $localisation;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("categorie")
     */
    private $categorie;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("url")
     */
    private $url;

    /**
     * @return null|string
     */
    public function getObjectType(): ?string
    {
        return $this->objectType;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return empty($this->title) ? $this->titre : $this->title;
    }

    /**
     * @return null|string
     */
    public function getRight(): ?string
    {
        return $this->right;
    }

    /**
     * @return null|string
     */
    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    /**
     * @return null|string
     */
    public function getMedia(): ?string
    {
        return $this->media;
    }

    /**
     * @return null|string
     */
    public function getUsedProfil(): ?string
    {
        return $this->usedProfil;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return null|string
     */
    public function getAccessDateFirstIssueOnline(): ?string
    {
        return $this->accessDateFirstIssueOnline;
    }

    /**
     * @return null|string
     */
    public function getAccessDateLastIssueOnline(): ?string
    {
        return $this->accessDateLastIssueOnline;
    }

    /**
     * @return null|string
     */
    public function getAccessNumFirstIssueOnline(): ?string
    {
        return $this->accessNumFirstIssueOnline;
    }

    /**
     * @return null|string
     */
    public function getAccessNumLastIssueOnline(): ?string
    {
        return $this->accessNumLastIssueOnline;
    }

    /**
     * @return null|string
     */
    public function getAccessNumFirstVolOnline(): ?string
    {
        return $this->accessNumFirstVolOnline;
    }

    /**
     * @return null|string
     */
    public function getAccessNumLastVolOnline(): ?string
    {
        return $this->accessNumLastVolOnline;
    }

    /**
     * @return null|string
     */
    public function getCote(): ?string
    {
        return $this->cote;
    }

    /**
     * @return null|string
     */
    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    /**
     * @return null|string
     */
    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    /**
     * @return null|string
     */
    public function getConservation():?string
    {
        $payload = [];
        if ($this->getAccessDateFirstIssueOnline()){
            $payload[] = $this->getAccessDateFirstIssueOnline();
        }
        if ($this->getAccessDateLastIssueOnline()){
            $payload[] = $this->getAccessDateLastIssueOnline();
        }
        if ($this->getAccessNumFirstIssueOnline()){
            $payload[] = $this->getAccessNumFirstIssueOnline();
        }
        if ($this->getAccessNumFirstVolOnline()){
            $payload[] = $this->getAccessNumFirstVolOnline();
        }
        if ($this->getAccessNumLastIssueOnline()){
            $payload[] = $this->getAccessNumLastIssueOnline();
        }
        if ($this->getAccessNumLastVolOnline()){
            $payload[] = $this->getAccessNumLastVolOnline();
        }
        if ($this->getUrl()){
            $payload[] = $this->getUrl();
        }
        if ($payload === []){
            return null;
        }

        return implode(', ', $payload);
    }
}
