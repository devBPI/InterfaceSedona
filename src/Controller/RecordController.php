<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RecordController extends AbstractController
{

    /**
     * @Route("/notice-bibliographique", name="record_bibliographic")
     */
    public function bibliographicRecordAction(Request $request)
    {
        return $this->render('record/bibliographic.html.twig', ['toolbar'=> 'document']);
    }

    /**
     * @Route("/notice-autorite", name="record_authority")
     */
    public function authorityRecordAction(Request $request)
    {
        return $this->render('record/authority.html.twig', ['toolbar'=> 'document']);
    }

    /**
     * @Route("/partager", name="record_share")
     */
    public function shareAction(Request $request)
    {
        return $this->render('record/share.html.twig', []);
    }

    /**
     * @Route("/impression", name="record_print")
     */
    public function printAction(Request $request)
    {
        return $this->render('record/print.html.twig', []);
    }

    /**
     * @Route("/exporter", name="record_export")
     */
    public function exportAction(Request $request)
    {
        return $this->render('record/export.html.twig', []);
    }
}
