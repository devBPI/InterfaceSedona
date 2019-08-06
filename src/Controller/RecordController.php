<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RecordController extends AbstractController
{

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
        return $this->render("record/bibliographic.$format.twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
        ]);
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
        return $this->render("record/authority.$format.twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
        ]);
    }

    /**
     * @Route("/partager", methods={"GET","HEAD"}, name="record_share")
     */
    public function shareAction(Request $request)
    {
        return $this->render('record/share.html.twig', []);
    }

    /**
     * @Route("/exporter", methods={"GET","HEAD"}, name="record_export")
     */
    public function exportAction(Request $request)
    {
        return $this->render('record/export.html.twig', []);
    }
}
