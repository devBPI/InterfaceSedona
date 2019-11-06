<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 31/10/19
 * Time: 11:23
 */

namespace App\Model\Traits;


trait BreadcrumbTrait
{
    /**
     * @return mixed
     */
    public function getBreadcrumbName():string
    {
        return self::BREAD_CRUMB_NAME;
    }
}
