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
use JMS\Serializer\Annotation as JMS;


class Picture implements PictureInterface
{

    use PathToContentTrait;
    /**
     * @var string
     *
     *
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("description")
     */
    private $description;

    /**
     * @var string
     *
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("url")
     *
     */
    private $url;

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
     return $this->pathToContent($this->url);
    }
}
