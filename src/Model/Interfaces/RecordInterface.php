<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 10/09/19
 * Time: 15:59
 */

namespace App\Model\Interfaces;


interface RecordInterface
{
    /**
     * @return string
     */
    public function getBreadcrumbName():string;

}
