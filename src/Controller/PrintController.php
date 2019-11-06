<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 18/10/19
 * Time: 17:24
 */

namespace App\Controller;


use App\Entity\UserSelectionDocument;
use App\Service\NoticeBuildFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Model\Form\ExportNotice;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class PrintController extends AbstractController
{
    /**
     * @var NoticeBuildFileService
     */
    private $buildFileContent;

    /**
     * PrintController constructor.
     * @param NoticeBuildFileService $buildFileContent
     */
    public function __construct(NoticeBuildFileService $buildFileContent )
    {
        $this->buildFileContent = $buildFileContent;
    }

    /**
     * @Route("/print/selection.{format}", methods={"GET","HEAD"}, name="selection_print", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     *
     * @param Request $request
     * @param $format
     * @return PdfResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function printSelection(Request $request, $format)
    {
        $sendAttachement = new ExportNotice();
        $sendAttachement
            ->setNotices($request->get('notices'))
            ->setAuthorities($request->get('authorities'))
            ->setImage($request->get('print-image', null) === 'print-image')
            ->setFormatType($format)
            ->setShortFormat($request->get('print-type', 'print-long') !== 'print-long')
        ;

        return  $this->buildFileContent->buildFile($sendAttachement, UserSelectionDocument::class);
    }


}
