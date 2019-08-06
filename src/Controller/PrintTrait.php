<?php


namespace App\Controller;


use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpFoundation\Response;

trait PrintTrait
{

    /**
     * @param $content
     * @param $filename
     * @param $format
     * @return Response
     * @throws \Spipu\Html2Pdf\Exception\Html2PdfException
     */
    protected function renderPrint($content, $filename, $format)
    {
        // TODO ici Elodie pour forcée le format de sortie
        $format = 'html';

        if ($format == 'txt') {
            return new Response($content,200,[
                'Content-Type' => 'application/force-download',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"'
            ]);
        } elseif ($format == 'html') {
            return new Response($content);
        }

        $html2pdf = new Html2Pdf();
        $html2pdf->writeHTML($content);
        $content = $html2pdf->output("/tmp/$filename.pdf",'S');

        return new Response($content,200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.pdf"'
        ]);

    }
}
