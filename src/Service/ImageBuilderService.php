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
    use TraitSlugify;

    private const IMAGE_FOLDER = 'images';
    public const PARENT_FOLDER = 'imported_images';
    public const BPI_FOLDER_NAME_ELECTRE = 'electre';
    public const DEFAULT_PICTURE = 'couvertures-generiques/cg-%s.svg';
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
     * @param string $type
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
     * @param string      $path
     * @param string|null $type
     *
     * @return null|string
     */
    public function getimage64(string $path, ?string $type = null): ?string
    {
        if ($type === null) {
            $type = 'livre';
        }
        $prefix = sprintf('imported_images%s%s%1$s', DIRECTORY_SEPARATOR, $type);
        $filePath = $this->buildImage(
            str_replace($prefix, '', $path),
            $type
        );
        $file = null;
        try {
            $file = new File($filePath, true);

            if (isset($file) && (!$file->isFile() || 0 !== strpos($file->getMimeType(), 'image/'))) {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }

        $binary = file_get_contents($filePath);

        if (false === $binary) {
            return null;
        }

        return sprintf('data:image/%s;base64,%s', $file->guessExtension(), base64_encode($binary));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function buildGenericPicture(string $type): string
    {
        $fs = new Filesystem();

        $filePath = self::PARENT_FOLDER.DIRECTORY_SEPARATOR;
        $filePath .= $this->slugify($type).DIRECTORY_SEPARATOR;
        $filePath .= sprintf(self::DEFAULT_PICTURE, $this->slugify($type));

        if ($fs->exists($filePath)) {
            return $filePath;
        }
        $fs->copy(
            self::IMAGE_FOLDER
            .DIRECTORY_SEPARATOR
            .sprintf(self::DEFAULT_PICTURE, $this->slugify($type)),
            $filePath
        );

        return $filePath;
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
}

