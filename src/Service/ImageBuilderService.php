<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ImageBuilderService
 * @package App\Service
 */
final class ImageBuilderService
{
    public  const PARENT_FOLDER              = 'imported_images';
    public const BPI_FOLDER_NAME_ELECTRE     = 'electre';
    public const DEFAULT_PICTURE             = 'couvertures-generiques/cg-%s.svg';
    public const THUMBNAIL = 'vignette';
    public  const COVER = 'couverture';

    /** @var string */
    private $imageDir;

    public static $url = 'http://10.1.2.120:8080';

    /**
     * ImageBuilderService constructor.
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

    public function getimage64($path)
    {
        try {
            $file = new File($this->imageDir.$path, true);
        } catch (\Exception $e) {

        }

        if (isset($file) && (!$file->isFile() || 0 !== strpos($file->getMimeType(), 'image/'))) {
            return null;
        }

        $binary = file_get_contents($this->imageDir.$path);

        return sprintf('data:image/%s;base64,%s', $file->guessExtension(), base64_encode($binary));
    }
}

