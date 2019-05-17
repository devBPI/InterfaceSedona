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
    /** @var string */
    private $imageDir;

    /** @var Filesystem */
    private $fs;

    /**
     * ImageService constructor.
     * @param string $imageDir
     */
    public function __construct(string $imageDir)
    {
        $this->imageDir = $imageDir;
        $this->fs = new Filesystem();
    }

    /**
     * @param string $url
     * @param string $folderName
     * @return string
     */
    public function getLocalPath(string $url, string $folderName): string
    {
        $localPath = $this->imageDir.$folderName.$this->extractPathFromUrl($url);

        if (!$this->fs->exists($localPath)) {
            $this->createFolderIfNotExist($this->imageDir.$folderName);
            $this->saveLocalImage($url, $localPath);
        }

        return $localPath;
    }

    /**
     * Extract filename from url
     * @param string $url
     * @return string
     */
    private function extractPathFromUrl(string $url): string
    {
        return parse_url($url, PHP_URL_PATH);
    }

    /**
     * Create folder if not exists
     * @param string $folderPath
     */
    private function createFolderIfNotExist(string $folderPath): void
    {
        if (!$this->fs->exists($folderPath)) {
            $this->fs->mkdir($folderPath);
        }
    }

    /**
     * Save file in local from url
     *
     * @param string $url
     * @param string $localPath
     * @return string
     */
    private function saveLocalImage(string $url, string $localPath): string
    {
        $this->fs->dumpFile($localPath, file_get_contents($url));
    }
}
