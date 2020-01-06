<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ImageBuilderService
 * @package App\Service
 */
final class ImageBuilderService
{
    use TraitSlugify;

    private const IMAGE_FOLDER = 'images';
    private const PARENT_FOLDER = 'imported_images';
    private const DEFAULT_PICTURE = 'couvertures-generiques/cg-%s.svg';
    private const BPI_FOLDER_NAME_ELECTRE = 'electre';

    public const THUMBNAIL = 'vignette';
    public const COVER = 'couverture';
    /**
     * @var string
     */
    public $url;
    /** @var string */
    private $imageDir;

    /**
     * ImageBuilderService constructor.
     *
     * @param string $imageDir
     * @param        $url
     */
    public function __construct(string $imageDir, string $url)
    {
        $this->imageDir = $imageDir;
        $this->url = $url;
    }

    /**
     * @param string $content
     * @param string $type
     *
     * @return string
     */
    public function buildImage(string $content, string $type = 'livre'): string
    {
        $localFilePath = $this->slugify($type).DIRECTORY_SEPARATOR.$content;

        $fs = new Filesystem();

        if (!$fs->exists($this->imageDir.$localFilePath)) {
            $filename = $content;

            try {
                $pictureURLparts = [$this->url, self::BPI_FOLDER_NAME_ELECTRE, $content];
                $content = file_get_contents(implode(DIRECTORY_SEPARATOR, $pictureURLparts));

                if ($content === false) {
                    return $this->buildGenericPicture($type);
                }
            } catch (\ErrorException $e) {
                return $this->buildGenericPicture($type);
            }

            $fs->mkdir(
                str_replace($filename, '', $this->imageDir.self::PARENT_FOLDER.DIRECTORY_SEPARATOR.$localFilePath)
            );
            $this->saveLocalImage($content, $localFilePath);
        }

        return $this->imageDir.self::PARENT_FOLDER.DIRECTORY_SEPARATOR.$localFilePath;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    static public function buildGenericPicture(string $type): string
    {
        return self::IMAGE_FOLDER.DIRECTORY_SEPARATOR.sprintf(self::DEFAULT_PICTURE, self::slugify($type));
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
     * @return int
     */
    public function clean():int
    {
        $finder = new Finder();
        $finder->in($this->imageDir.ImageBuilderService::PARENT_FOLDER)->date('< now - 24 hours')->files();
        $count = $finder->count();

        $fs = new Filesystem();
        $fs->remove($finder);

        return $count;
    }
}

