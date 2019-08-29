<?php


namespace App\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use App\Model\Notice;
use App\Service\Provider\NoticeProvider;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecordController extends AbstractController
{


    /**
     * @var NoticeProvider
     */
    private $noticeProvider;

    /**
     * RecordController constructor.
     * @param NoticeProvider $noticeProvider
     */
    public function __construct(NoticeProvider $noticeProvider)
    {
        $this->noticeProvider = $noticeProvider;
    }

    /**
     * @Route("/notice-bibliographique", methods={"GET","HEAD"}, name="record_bibliographic")
     */
    public function bibliographicRecordAction(Request $request)
    {
        $query = $request->get('ark');

        $object = $this->noticeProvider->getNotice($query);


        //$objSearch->setQuery($query);
/*


        return $this->render('search/index.html.twig', [
            'toolbar'       => 'search',
            'objSearch'     => $objSearch,
            'printRoute'    => $this->generateUrl('search_pdf')
        ]);
*/
        return $this->render('record/bibliographic.html.twig', [
            'object'     => $object,
            'toolbar'       => 'document',
            'printRoute'    => $this->generateUrl('record_bibliographic_pdf',['format'=> 'pdf'])
        ]);
    }

    /**
     * @Route("/print/notice-bibliographique.{format}", methods={"GET","HEAD"}, name="record_bibliographic_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function bibliographicRecordPDFAction(Request $request, \Knp\Snappy\Pdf $knpSnappy, $format = "pdf")
    {
        $content = $this->renderView("record/bibliographic.".($format == 'txt' ? 'txt': 'pdf').".twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
        ]);
        $filename = 'bibliographic'.date('Y-m-d_h-i-s');

        if ($format == 'txt') {
            return new Response($content,200,[
                'Content-Type' => 'application/force-download',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"'
            ]);
        } elseif ($format == 'html') {
            return new Response($content);
        }

        return new PdfResponse(
            $knpSnappy->getOutputFromHtml($content),
            $filename.".pdf"
        );
    }

    /**
     * @Route("/notice-autorite", methods={"GET","HEAD"}, name="record_authority")
     */
    public function authorityRecordAction(Request $request)
    {
        return $this->render('record/authority.html.twig', [
            'toolbar'       => 'document',
            'printRoute'    => $this->generateUrl('record_authority_pdf')
        ]);
    }

    /**
     * @Route("/print/notice-autorite.{format}", name="record_authority_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function authorityRecordPDFAction(Request $request, \Knp\Snappy\Pdf $knpSnappy, $format = "pdf")
    {
        $content = $this->renderView("record/authority.".($format == 'txt' ? 'txt': 'pdf').".twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
        ]);
        $filename = 'authority_'.date('Y-m-d_h-i-s');

        if ($format == 'txt') {
            return new Response($content,200,[
                'Content-Type' => 'application/force-download',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"'
            ]);
        } elseif ($format == 'html') {
            return new Response($content);
        }

        return new PdfResponse(
            $knpSnappy->getOutputFromHtml($content),
            $filename.".pdf"
        );
    }
}
