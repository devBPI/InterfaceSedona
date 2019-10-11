<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 10/10/19
 * Time: 17:24
 */

namespace App\Model;


use App\Service\ImageBuilderService;

trait ImageIsbnTrait
{
    /**
     * @return string
     */
    public function getIsbnCover()
    {
        return ImageBuilderService::COVER.DIRECTORY_SEPARATOR.$this->getIsbn();
    }

    /**
     * @return string
     */
    public function getIsbnThumbnail()
    {
        return ImageBuilderService::THUMBNAIL.DIRECTORY_SEPARATOR.$this->getIsbn();
    }

}
