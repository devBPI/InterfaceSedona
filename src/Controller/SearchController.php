<?php


namespace App\Controller;

use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    use PrintTrait;

    /**
     * @Route("/recherche", methods={"GET","HEAD"}, name="search")
     */
    public function indexAction(Request $request)
    {
        return $this->render('search/index.html.twig', [
            'toolbar'       => 'search',
            'printRoute'    => $this->generateUrl('search_pdf')
        ]);
    }

    /**
     * @Route("/recherche-tout", methods={"GET","HEAD"}, name="search_all")
     */
    public function searchAllAction(Request $request)
    {
        // TODO: controleur provisoire destiné a afficher une mise en page spécifique
        return $this->render('search/index-all.html.twig', [
            'toolbar'       => 'search',
            'printRoute'    => $this->generateUrl('search_pdf')
        ]);
    }

    /**
     * @Route("/recherche.{format}", methods={"GET","HEAD"}, name="search_pdf", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function printAction(Request $request, $format)
    {
        $content = $this->renderView("search/index.".($format == 'txt' ? 'txt': 'pdf').".twig", [
            'isPrintLong'   => $request->get('print-type', 'print-long') == 'print-long',
            'includeImage'  => $request->get('print-image', null) == 'print-image',
        ]);

        return $this->renderPrint($content,'search'.date('Y-m-d_h-i-s'), $format );
    }

    /**
     * @Route("/recherche-avance", methods={"GET","HEAD"}, name="search_advanced")
     */
    public function advancedSearchAction(Request $request)
    {
        return $this->render('search/modal/advanced-search.html.twig', []);
    }

    /**
     * @Route("/autocompletion", methods={"GET","HEAD"}, name="search_autocompletion")
     */
    public function autocompletionAction(Request $request)
    {
        return $this->render('search/autocompletion.html.twig', []);
    }

}
