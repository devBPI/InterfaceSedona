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

    public  const PARENT_FOLDER             = 'imported_images';
    public  const COVER_FOLDER              = 'couvertures-generiques';
    private const IMAGE_FOLDER              = 'images';
    public const BPI_FOLDER_NAME_ELECTRE    = 'electre';
    public const DEFAULT_PICTURE            = 'couvertures-generiques/cg-%s.svg';
    public const THUMBNAIL = 'vignette';
    public  const COVER = 'couverture';

    /** @var string */
    private $imageDir;
    /**
     * @var string
     */
    public  $url;

    /**
     * ImageBuilderService constructor.
     * @param string $imageDir
     * @param $url
     */
    public function __construct(string $imageDir, string $url)
    {
        $this->imageDir = $imageDir;
        $this->url = $url;
    }

    /**
     * @param string $content
     * @param string $type
     * @return string
     */
    public function buildImage(string $content, string $type='livre'): string
    {
        $localFilePath = $this->slugify($type).DIRECTORY_SEPARATOR.$content;
        $fs = new Filesystem();

        if (!$fs->exists($this->imageDir.$localFilePath)) {
            $filename = $content;

           try{
               if (($content = file_get_contents($this->url.DIRECTORY_SEPARATOR.self::BPI_FOLDER_NAME_ELECTRE.DIRECTORY_SEPARATOR.$content)) === false){
                   return $this->buildGenericPicture($type);
               }
           }catch (\ErrorException $e){
                return $this->buildGenericPicture($type);
           }

           $fs->mkdir(str_replace($filename, '', $this->imageDir.self::PARENT_FOLDER.DIRECTORY_SEPARATOR.$localFilePath));
           $this->saveLocalImage($content, $localFilePath);
        }

        return $this->imageDir.self::PARENT_FOLDER.DIRECTORY_SEPARATOR.$localFilePath;
    }

    /**
     * @param string $type
     * @return string
     */
    private function buildGenericPicture(string $type)
    {
        $fs = new Filesystem();
        $filePath = self::PARENT_FOLDER.DIRECTORY_SEPARATOR.$this->slugify($type).DIRECTORY_SEPARATOR.self::COVER_FOLDER.DIRECTORY_SEPARATOR.'cg-'.$this->slugify($type).'.svg';
        if ($fs->exists($filePath)){
            return $filePath;
        }
        $fs->copy( self::IMAGE_FOLDER.DIRECTORY_SEPARATOR.self::COVER_FOLDER.DIRECTORY_SEPARATOR.'cg-'.$this->slugify($type).'.svg', $filePath);

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

    /**
     * @param string $path
     * @param string|null $type
     * @return null|string
     */
    public function getimage64(string $path, string $type=null)
    {
        if ($type===null){
            $type='livre';
        }
        $filePath = $this->buildImage(str_replace("imported_images".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR,"", $path), $type);
        $file = null;
        try {
            $file = new File($filePath, true);
        } catch (\Exception $e) {

        }

        if (isset($file) && (!$file->isFile() || 0 !== strpos($file->getMimeType(), 'image/'))) {
            return null;
        }

         $binary = file_get_contents($filePath);

        return sprintf('data:image/%s;base64,%s', $file->guessExtension(), base64_encode($binary));
    }
}

