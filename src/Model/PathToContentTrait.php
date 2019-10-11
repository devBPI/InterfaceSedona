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

    private function pathToContent($path):string
    {
        return str_replace(ImageBuilderService::$url . DIRECTORY_SEPARATOR . ImageBuilderService::BPI_FOLDER_NAME_ELECTRE . DIRECTORY_SEPARATOR, '', $path);
    }
}
