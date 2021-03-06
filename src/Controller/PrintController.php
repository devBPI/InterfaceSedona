<?php

namespace App\Controller;


use App\Entity\UserSelectionDocument;
use App\Model\Form\ExportNotice;
use App\Service\NoticeBuildFileService;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class PrintController
 * @package App\Controller
 */
final class PrintController extends AbstractController
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
     * @param string $format
     * @return PdfResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function printSelection(Request $request, string $format='pdf')
    {
        $sendAttachement = new ExportNotice();
        $sendAttachement
            ->setNotices($request->get('notices'))
            ->setAuthorities($request->get('authorities'))
            ->setIndices($request->get('indices'))
            ->setImage($request->get('print-image', null) === 'print-image')
            ->setFormatType($format)
            ->setShortFormat($request->get('print-type', 'print-long') !== 'print-long')
        ;

        return $this->buildFileContent->buildFile($sendAttachement, UserSelectionDocument::class);
    }
}
