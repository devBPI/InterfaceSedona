<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Exception\ApiException;
use App\Model\Exception\BPIException;
use App\Model\Exception\ErrorAccessApiException;
use App\Model\Exception\NoResultException;
use App\Service\APIClient\CatalogClient;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
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
     * @param string $content
     * @param string $type
     *
     * @return string
     */
    public function buildImage(string $content, string $type = 'livre'): string
    {
        $localFilePath = $this->imageDir.self::PARENT_FOLDER.DIRECTORY_SEPARATOR.$this->slugify($type).DIRECTORY_SEPARATOR.$content.".jpg";

        $fs = new Filesystem();
        if (!$fs->exists($localFilePath)) {
            try {
                $response = $this->catalogClient->get(self::BPI_FOLDER_NAME_ELECTRE.DIRECTORY_SEPARATOR.$content);
                if ($response->getStatusCode() === 200) {
                    $content = $response->getBody()->getContents();
                    if (!empty($content)) {
                        $fs->dumpFile($localFilePath, $content);
                    }
                }
            } catch (BPIException|NotFoundHttpException|AccessDeniedException $e) {}
        }

        if (!$fs->exists($localFilePath)) {
            $fs->copy($this->imageDir.$this->buildGenericPicture($type),$localFilePath );
        }

        return $localFilePath;
    }

    /**
     * @param string $type
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
}

