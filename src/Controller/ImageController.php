<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 08/10/19
 * Time: 15:39
 */

namespace App\Controller;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageController extends AbstractController
{

    /**
     * @Route("/{noticeType}/{content}/{folderName}/{fileName}", "name"="image_binaire")
     *
     *
     **/
    public function get()
    {

    }



    /**
     * @param string $url
     * @param string $folderName
     * @return string
     */
    protected function saveLocalImageFromUrl(string $url, string $folderName): string
    {
        try {
            return $this->imageService->getLocalPath(file_get_contents($url), $folderName, $this->imageService->extractPathFromUrl($url));
        } catch (\ErrorException $exception) {
            // Log error
        }

        return $url;
    }

    /**
     * @param string $content
     * @param string $folderName
     * @param string $fileName
     * @return string
     */
    protected function saveLocalImageFromContent(string $content, string $folderName, string $fileName): string
    {
        try {
            return $this->imageService->getLocalPath($content, $folderName, $fileName);
        } catch (\ErrorException $exception) {
            // Log error
        }

        return '';
    }
}
