<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 30/08/19
 * Time: 14:26
 */
declare(strict_types=1);

namespace App\Model;
use App\Model\Interfaces\PictureInterface;
use App\Service\ImageBuilderService;
use JMS\Serializer\Annotation as JMS;


class Picture implements PictureInterface
{

    use PathToContentTrait;
    /**
     * @var string
     * @JMS\TYPE("string")
     *
     */
    private $description;

    /**
     * @var string
     * @JMS\TYPE("string")
     *
     */
    private $url;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        $this->pathToContent($this->url);
    }
}
