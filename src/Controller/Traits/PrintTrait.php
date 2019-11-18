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
     * @param string $content
     * @param string $filename
     * @param string $format
     * @return PdfResponse|Response
     */
    protected function renderPrint(string $content,  string $filename, string $format)
    {
        if ($format === 'txt') {
            return new Response($content,200,[
                'Content-Type' => 'application/force-download',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.txt"'
            ]);
        } elseif ($format === 'html') {
            return new Response($content);
        }

        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($content),
            "$filename.pdf"
        );
    }
}
