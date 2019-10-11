<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 11/10/19
 * Time: 14:27
 */

namespace App\Service;


use App\Exception\DirectoryNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class CleanerFiles
 * @package App\Service
 */
class CleanerFiles
{
    /** @var string */
    private $imageDir;

    /**
     * @param string $imageDir
     */
    public function setImageDir(string $imageDir)
    {
        $this->imageDir = $imageDir;
    }

    /**
     * ImageBuilderService constructor.
     * @param string $imageDir
     */
    public function __construct(string $imageDir)
    {
        $this->imageDir = $imageDir;
    }

    /**
     * @return int
     */
    public function clean():int
    {
         return $this->removeFilesFrom(ImageBuilderService::THUMBNAIL) + $this->removeFilesFrom(ImageBuilderService::COVER);
    }

    /**
     * @param $directory
     * @return int
     */
    private function removeFilesFrom($directory):int
    {
        $baseDir = $this->imageDir.ImageBuilderService::PARENT_FOLDER.DIRECTORY_SEPARATOR;

        $fs = new Filesystem();
        if (!$fs->exists($baseDir.$directory)){
            throw new DirectoryNotFoundException($baseDir.$directory);
        }
        $files = scandir($baseDir.$directory);
        $filesToNotRemove = array_slice ($files,0,3 );
        $filesToRemove = array_diff($files, $filesToNotRemove);
        $count = count( $filesToRemove);

        foreach ($filesToRemove as $file){
            $fs->remove($baseDir.$directory.DIRECTORY_SEPARATOR.$file);
        }

        return $count;
    }

}
