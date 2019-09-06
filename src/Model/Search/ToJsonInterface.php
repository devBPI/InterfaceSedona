<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 04/09/19
 * Time: 18:06
 */

namespace App\Model\Search;


interface ToJsonInterface
{
    /**
     * @return string
     */
    public function toJson():string;
}