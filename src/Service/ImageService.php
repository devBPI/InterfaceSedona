<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ImageService
 * @package App\Service
 */
final class ImageService
{
    private const PARENT_FOLDER = 'imported_images';

    /** @var string */
    private $imageDir;

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
     * @param string $folderName
     * @param string $fileName
     * @return string
     */
    public function getLocalPath(string $content, string $folderName, string $fileName): string
    {
        $folderPath = self::PARENT_FOLDER.DIRECTORY_SEPARATOR.$folderName;
        $localFilePath = $folderPath.DIRECTORY_SEPARATOR.$fileName;

        $fs = new Filesystem();
        if (!$fs->exists($this->imageDir.$localFilePath)) {
            $this->createFolderIfNotExist($folderPath);
            $this->saveLocalImage($content, $localFilePath);
        }

        return $localFilePath;
    }

    /**
     * Extract filename from url
     * @param string $url
     * @return string
     */
    public function extractPathFromUrl(string $url): string
    {
        return parse_url($url, PHP_URL_PATH);
    }

    /**
     * Create folder if not exists
     * @param string $folderPath
     */
    private function createFolderIfNotExist(string $folderPath): void
    {
        $fs = new Filesystem();
        if (!$fs->exists($this->imageDir.$folderPath)) {
            $fs->mkdir($this->imageDir.$folderPath);
        }
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
        $fs->dumpFile($this->imageDir.$localPath, $content);
    }
}
