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
     * @param $path
     * @return string
     */
    private function pathToContent(?string $path):?string
    {
	if(null == $path)
		return null;
        return ImageBuilderService::COVER.'/'.basename($path);
    }
}
