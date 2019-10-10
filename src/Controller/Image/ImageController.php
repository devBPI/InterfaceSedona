<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 08/10/19
 * Time: 15:39
 **/

namespace App\Controller\Image;

use App\Service\ImageBuilderService;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImageController extends AbstractController
{
    /**
     * @var ImageBuilderService
     */
    private $imageService;

    /**
     * ImageController constructor.
     * @param ImageBuilderService $imageService
     */
    public function __construct(ImageBuilderService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @param $content
     * @return BinaryFileResponse
     */
    public function binaryAction($content)
    {
        $filePath = $this->imageService->buildImage($content);
        $filePath  = ImageBuilderService::PARENT_FOLDER.DIRECTORY_SEPARATOR.$filePath;
        $mimeTypeGuesser = new FileinfoMimeTypeGuesser();

        $headers = [
            'Content-Type'        => $mimeTypeGuesser->guess($filePath),
            'Content-Disposition' => 'inline; filename="2-07-021151-7"'
        ];

        return new BinaryFileResponse($filePath, 200, $headers);
    }
}

