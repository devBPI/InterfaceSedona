<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ImageService
 * @package App\Service
 */
final class ImageBuilderService
{

    public  const PARENT_FOLDER              = 'imported_images';
    public const BPI_FOLDER_NAME_ELECTRE     = 'electre';
    public const DEFAULT_PICTURE             = 'couvertures-generiques/cg-%s.svg';
    public  const  ITEM                      = 'article';
    public  const  BD                        = 'bd';
    public  const  CARD                      = 'carte';
    public  const   DEBATE                   = 'debat';
    public  const  PRESS_PACK                = 'dossier-presse';
    public  const  EVENT                     = 'evenement';
    public  const   FORMATION                = 'formation';
    public  const   IMAGE                    = 'image';
    public  const   JOURNAL                  = 'journal';
    public  const   JOURNAL_MAGAZINE         = 'revue-journal';
    public  const   BOOK                     = 'livre';
    public  const   AUDIO_BOOK               = 'livre-audio';
    public  const   DIGITAL_BOOK             = 'livre-numerique';
    public  const   MUSIQUE                  = 'musique';
    public  const   PARTITION                = 'parition';
    public  const   DIGITAL_MAGAZINE         = 'revue-numerique';
    public  const   SITE                     = 'site';
    public  const   VIDEO                    = 'video';

    /** @var string */
    private $imageDir;

    public static $url = 'http://10.1.2.120:8080';
    /**
     * ImageService constructor.
     * @param string $imageDir
     */
    public function __construct(string $imageDir)
    {
        $this->imageDir = $imageDir;
    }

    /**
     * @param string $content
     * @return string
     */
    public function buildImage(string $content): string
    {
        $localFilePath = $content;

        $fs = new Filesystem();

        if (!$fs->exists($this->imageDir.$localFilePath)) {
            $array = explode('/', $content);
            $filename = array_pop($array);

            $fs->mkdir(str_replace($filename, '', $this->imageDir.self::PARENT_FOLDER.DIRECTORY_SEPARATOR.$localFilePath));
            $content = file_get_contents(self::$url.DIRECTORY_SEPARATOR.self::BPI_FOLDER_NAME_ELECTRE.DIRECTORY_SEPARATOR.$content);
            $this->saveLocalImage($content, $localFilePath);
        }

        return $localFilePath;
    }

    /**
     * Save file in local from content
     *
     * @param string $content
     * @param string $localPath
     */
    private function saveLocalImage(string $content, string $localPath): void
    {
        $fs = new Filesystem();
        $fs->dumpFile($this->imageDir.self::PARENT_FOLDER.DIRECTORY_SEPARATOR.$localPath, $content);
    }

    /**
     * @param string $content
     * @return string
     */
    public function getDefaultImage(string $content)
    {
        return sprintf(ImageBuilderService::DEFAULT_PICTURE, $content);
    }
}

