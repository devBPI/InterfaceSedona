<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 10/10/19
 * Time: 14:52
 */

namespace App\Model;



trait PathToContentTrait
{
    /**
     * @param $path
     * @return string
     */
    private function pathToContent($path):string
    {
        return basename($path);
    }
}
