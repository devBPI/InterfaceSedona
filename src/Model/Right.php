<?php
declare(strict_types=1);

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
    public function __toArray(): array
    {
        return array_merge($this->rights, $this->licence, $this->copyRight);
    }

}
