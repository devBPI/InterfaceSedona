<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 14/08/19
 * Time: 15:56
 */

namespace App\Model\Traits;

use JMS\Serializer\Annotation as JMS;

trait NoticeMappedTrait
{
    /**
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("editeurs")
     * @JMS\XmlList("editeur")
     * */
    private $publisher;

    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("configurationId")
     */
    private $configurationId;


    /**
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("formats")
     * @JMS\XmlList("format")
     **/
    private $formats;


    /**
     * @var string
     * @JMS\Type("string")
     * @JMS\SerializedName("sourceId")
     */
    private $sourceId;

    /**
     * @return mixed
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @return string
     */
    public function getConfigurationId(): string
    {
        return $this->configurationId;
    }

    /**
     * @return mixed
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * @return string
     */
    public function getSourceId(): string
    {
        return $this->sourceId;
    }


}
