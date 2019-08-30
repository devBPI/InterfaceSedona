<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: infra
 * Date: 29/08/19
 * Time: 16:40
 */
namespace App\Model;

use JMS\Serializer\Annotation as JMS;

class Cdu
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    private $cote;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("libelle")
     */
    private $name;
    /**
     * @return string
     */
    public function getCote(): string
    {
        return $this->cote;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return trim($this->name, '[]');
    }
}
