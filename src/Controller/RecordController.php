<?php


namespace App\Controller;

use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecordController extends AbstractController
{
    use PrintTrait;

    /**
     * @Route("/notice-bibliographique", methods={"GET","HEAD"}, name="record_bibliographic")
     */
    public function bibliographicRecordAction(Request $request)
    {
        return $this->render('record/bibliographic.html.twig', [
            'toolbar'       => 'document',
            'printRoute'    => $this->generateUrl('record_bibliographic_pdf')
        ]);
    }

    /**
     * @Route("/notice-bibliographique.{format}", methods={"GET","HEAD"}, name="record_bibliographic_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function bibliographicRecordPDFAction(Request $request, $format = "pdf")
    {
        $content = $this->renderView("record/bibliographic.".($format == 'txt' ? 'txt': 'pdf').".twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
        ]);

        return $this->renderPrint($content,'bibliographic'.date('Y-m-d_h-i-s'), $format );
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
     * @Route("/notice-autorite.{format}", methods={"GET","HEAD"}, name="record_authority_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function authorityRecordPDFAction(Request $request, $format = "pdf")
    {
        $content = $this->renderView("record/authority.".($format == 'txt' ? 'txt': 'pdf').".twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
        ]);

        return $this->renderPrint($content,'authority'.date('Y-m-d_h-i-s'), $format );
    }
}
