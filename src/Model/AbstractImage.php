<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 10/10/19
 * Time: 14:49
 */

namespace App\Model;


abstract class AbstractImage
{
    /**
     * @return string
     */
    abstract  public function getImage(): ?string;
}
