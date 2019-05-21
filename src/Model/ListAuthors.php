<?php

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class ListAuthors
 * @package App\Model
 */
class ListAuthors
{
    /**
     * @var array|Author[]
     * @JMS\Type("array<App\Model\Author>")
     * @JMS\XmlList(entry="authority")
     */
    private $authoritiesList = [];

    /**
     * @return array|Author[]
     */
    public function getAuthoritiesList(): array
    {
        return $this->authoritiesList;
    }

}
