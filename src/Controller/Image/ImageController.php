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
use Symfony\Component\HttpFoundation\Request;

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
     * @param string $content
     * @param string $type
     * @return BinaryFileResponse
     */
    public function binary(string $content, string $type): BinaryFileResponse
    {
        $filePath = $this->imageService->buildImage($content, $type);
        return new BinaryFileResponse($filePath);
    }
}

