<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 05/09/19
 * Time: 10:51
 */

namespace App\Model\Search;


interface FromArrayInterface
{
    /**
     * @param array $array
     * @return mixed
     */
    public static function fromArray($array);
}