<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 10/10/19
 * Time: 14:52
 */

namespace App\Model;



use App\Service\ImageBuilderService;

trait PathToContentTrait
{
    /**
     * @param string $path
     * @return string
     */
    private function pathToContent(string  $path):string
    {
        return ImageBuilderService::COVER.'/'.basename($path);
    }
}
