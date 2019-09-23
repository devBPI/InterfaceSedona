<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 20/09/19
 * Time: 17:04
 */

namespace App\Model;

use JMS\Serializer\Annotation as JMS;


class Right
{

    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("droits")
     * @JMS\XmlList("droit")
     */
    private $rights;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("copyrights")
     * @JMS\XmlList("copyright")
     */
    private $copyRight;
    /**
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("licences")
     * @JMS\XmlList("licence")
     */
    private $licence;

    /**
     * @return array
     */
    public function getRights(): array
    {
        return $this->rights;
    }

    /**
     * @return array
     */
    public function getCopyRight(): array
    {
        return $this->copyRight;
    }

    /**
     * @return array
     */
    public function getLicence(): array
    {
        return $this->licence;
    }

    /**
     * @return string
     */
    public function __toString():string
    {
        $payload = [];
        if (count($this->getRights()) >0){
            $payload[] = implode(',', $this->getRights());
        }
        if (count($this->getLicence()) >0){
            $payload[] = implode(',', $this->getLicence());
        }
        if (count($this->getCopyRight()) >0){
            $payload[] = implode(',', $this->v());
        }

        if (count($payload)>0){

            return implode(',', $payload);
        }

        return '';
    }

}
