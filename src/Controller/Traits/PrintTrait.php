<?php


namespace App\Controller\Traits;


use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait PrintTrait
 * @package App\Controller
 */
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
        //$format = 'html';

        if ($format == 'txt') {
            return new Response($content,200,[
                'Content-Type' => 'application/force-download',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"'
            ]);
        } elseif ($format == 'html') {
            return new Response($content);
        }

        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($content),
            "$filename.pdf"
        );
    }
}
