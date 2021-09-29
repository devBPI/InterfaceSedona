<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 02/09/19
 * Time: 17:01
 */

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Quatrieme
 * @package App\Model
 */
class Quatrieme
{
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("presentation")
     * @JMS\XmlList("p")
     */
    private $presentation;

     /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("biographie")
     */
    private $biography;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("infoediteur")
     */
    private $editorInfo;

    /**
     * @return null|string
     */
    public function getPresentation(): ?string
    {
        //return $this->presentation?implode("<br />", $this->presentation):null;
        return $this->presentation?$this->presentation[0]:null;
    }

    /**
     * @return null|string
     */
    public function getBiography(): ?string
    {
        return $this->biography;
    }

    /**
     * @return null|string
     */
    public function getEditorInfo(): ?string
    {
        return $this->editorInfo;
    }

}
