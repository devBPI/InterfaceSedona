<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 30/08/19
 * Time: 17:20
 */

namespace App\Model;


interface ValueBPIInterface
{

    /**
     * @return null|string
     */
    public function getValue():?string;
}