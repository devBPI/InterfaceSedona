<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Exception\BPIException;
use App\Service\APIClient\CatalogClient;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ImageBuilderService
 * @package App\Service
 */
final class ImageBuilderService
{
    use TraitSlugify;

    private const IMAGE_FOLDER = 'images';
    private const PARENT_FOLDER = 'imported_images';
    private const DEFAULT_PICTURE = 'couvertures-generiques/cg-%s.jpg';
    private const BPI_FOLDER_NAME_ELECTRE = 'electre';

    public const THUMBNAIL = 'vignette';
    public const COVER = 'couverture';

    /** @var string */
    private $imageDir;

    /**
     * @var CatalogClient
     */
    private $catalogClient;

    /**
     * ImageBuilderService constructor.
     *
     * @param string $imageDir
     * @param        $url
     */
    public function __construct(string $imageDir, CatalogClient $catalogClient)
    {
        $this->imageDir = $imageDir;
        $this->catalogClient = $catalogClient;
    }

    /**
     * @param string $url
     * @param string $type
     *
     * @return string
     */
    public function buildImage(string $url, string $type = 'livre'): string
    {
        $localFilePath = $this->getLocalFilePathFromUrl($url, $type);
        $fs = new Filesystem();
        if (!$fs->exists($localFilePath)) {
            try {
                $content = $this->getContentFromUrl($url);
                if (!empty($content)) {
                    $fs->dumpFile($localFilePath, $content);
                }
            } catch (BPIException|NotFoundHttpException|AccessDeniedException $e) {}
        }

        if (!$fs->exists($localFilePath)) {
            $fs->copy($this->imageDir.$this->buildGenericPicture($type),$localFilePath );
        }

        return $localFilePath;
    }

    /**
     * @param string|null $type
     *
     * @return string
     */
    static public function buildGenericPicture(?string $type): string
    {
        return self::IMAGE_FOLDER.DIRECTORY_SEPARATOR.sprintf(self::DEFAULT_PICTURE, self::slugify($type));
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

    private function getLocalFilePathFromUrl(string $url, string $type): string
    {
        $title = $url;
        if (strpos($url, 'http') === 0) {
            $urlParts = parse_url($url);
            $title = trim($urlParts['host'], "/").DIRECTORY_SEPARATOR.trim($urlParts['path'], "/");
            $title = substr($title, 0, strrpos($title, "."));
        }

        return $this->imageDir.self::PARENT_FOLDER.DIRECTORY_SEPARATOR.$this->slugify($type).DIRECTORY_SEPARATOR.$title.".jpg";
    }

    private function getContentFromUrl(string $url): ?string
    {
        if (strpos($url, 'http') === 0) {
            return file_get_contents($url);
        }

        $response = $this->catalogClient->get(self::BPI_FOLDER_NAME_ELECTRE.DIRECTORY_SEPARATOR.$url);
        if ($response->getStatusCode() === 200) {
            return $response->getBody()->getContents();
        }

        return null;
    }
}

